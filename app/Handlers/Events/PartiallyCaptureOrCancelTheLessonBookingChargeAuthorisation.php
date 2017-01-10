<?php

namespace App\Handlers\Events;

use App\Tutor;
use App\Student;
use Carbon\Carbon;
use App\LessonBooking;
use App\Billing\FeeCalculator;
use Illuminate\Database\DatabaseManager;
use App\Events\LessonBookingWasCancelled;
use App\Billing\Contracts\BillingInterface;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class PartiallyCaptureOrCancelTheLessonBookingChargeAuthorisation extends EventHandler
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var BillingInterface
     */
    protected $billing;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * @var FeeCalculator
     */
    protected $fee;

    /**
     * Create an instance of this
     *
     * @param  Auth                             $auth
     * @param  DatabaseManager                  $database
     * @param  BillingInterface                 $billing
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  FeeCalculator                    $fee
     * @return void
     */
    public function __construct(
        Auth                             $auth,
        DatabaseManager                  $database,
        BillingInterface                 $billing,
        LessonBookingRepositoryInterface $bookings,
        FeeCalculator                    $fee
    ) {
        $this->auth     = $auth;
        $this->database = $database;
        $this->billing  = $billing;
        $this->bookings = $bookings;
        $this->fee      = $fee;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasCancelled $event
     * @return void
     */
    public function handle(LessonBookingWasCancelled $event)
    {
        return $this->database->transaction(function () use ($event) {
            $user         = $this->auth->user();
            $booking      = $event->booking;
            $lesson       = $booking->lesson;
            $relationship = $lesson->relationship;
            $student      = $relationship->student;
            $tutor        = $relationship->tutor;
            $charge       = $this->findCharge($student, $booking);

            if ( ! $charge) {
                loginfo('no charge - return');
                return;
            }

            if (
                $user instanceof Student &&
                $this->shouldBePartiallyCharged($booking)
            ) {
                loginfo('Started refund');
                // Tutora fee & cancellation discount
                $discount = config('fees.cancellation');
                $fee      = $this->fee->calculateByTutor($tutor);
                // Capture
                $charge->discount($discount);
                $charge->fee($fee);
                $charge->capture();
                // Vent
                $booking = LessonBooking::paidPartially($booking, $charge);
                loginfo('Finished refund');
            } else {
                // Refund
                $charge->refund();
                // Vent
                $booking = LessonBooking::refunded($booking);
            }

            $this->bookings->save($booking);
        });

    }

    /**
     * Find the charge for a given student & booking
     *
     * @param  Student $student
     * @param  LessonBooking $booking
     * @return StripeCharge|null
     */
    protected function findCharge(Student $student, LessonBooking $booking)
    {
        if ($booking->getChargeId()) {
            $customer = $this->billing->account($student);
            return $this->billing->charge($customer, $booking);
        }
    }

    /**
     * Should the lesson booking be partially charged?
     *
     * @param  LessonBooking $booking
     * @return boolean
     */
    protected function shouldBePartiallyCharged(LessonBooking $booking)
    {
        $minutes = config('lesson.cancel_period', 720);
        $date    = Carbon::now()->addMinutes($minutes);

        return $booking->start_at->lt($date);
    }

}


<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\LessonBooking;
use App\Billing\FeeCalculator;
use Illuminate\Database\DatabaseManager;
use App\Billing\Contracts\BillingInterface;
use App\Billing\Contracts\Exceptions\BillingExceptionInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class ChargeLessonBookingPaymentsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:charge_lesson_booking_payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Charge payments on lesson bookings.';

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * @var BillingInterface
     */
    protected $billing;

    /**
     * Create a new command instance.
     *
     * @param  DatabaseManager                  $database
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  BillingInterface                 $billing
     * @param  FeeCalculator                    $fee
     * @return void
     */
    public function __construct(
        DatabaseManager                  $database,
        LessonBookingRepositoryInterface $bookings,
        BillingInterface                 $billing,
        FeeCalculator                    $fee
    ) {
        parent::__construct();

        $this->database = $database;
        $this->bookings = $bookings;
        $this->billing  = $billing;
        $this->fee      = $fee;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        loginfo("[ Background ] {$this->name}");

        // Lookup
        $date     = $this->getDate();
        $now      = Carbon::now();
        $bookings = $this->bookings->getChargableBeforeDate($date);
        // Loopy
        foreach ($bookings as $booking) {

            $this->database->transaction(function () use ($booking, $date, $now) {
                // Lookups
                $lesson       = $booking->lesson;
                $relationship = $lesson->relationship;
                $student      = $relationship->student;
                $tutor        = $relationship->tutor;
                // Billing
                try {
                    // Accounts
                    $customer = $this->billing->account($student);
                    $payee    = $this->billing->account($tutor);
                    // Tutora fee
                    $fee = $this->fee->calculateByTutor($tutor);
                    // Capture
                    $charge = $this->billing->charge($customer, $booking);
                    $charge->payee($payee);
                    $charge->fee($fee);
                    $charge->now();
                    // Vent
                    LessonBooking::paid($booking, $charge);
                } catch (BillingExceptionInterface $e) {
                    LessonBooking::paymentFailed($booking, $now, $e);
                }
                // Dispatch
                $this->dispatchFor($booking);
                // Save
                $this->bookings->save($booking);

            });
        }
        
    }

    /**
     * Get the date to look for bookings before
     *
     * @return Carbon
     */
    protected function getDate()
    {
        $minutes = config('lessons.capture_payment_period', -1440);

        return Carbon::now()->addMinutes($minutes);
    }

}

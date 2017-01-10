<?php

namespace App\Handlers\Events;

use Carbon\Carbon;
use App\LessonBooking;
use App\Events\UserCardWasUpdated;
use App\Billing\Contracts\BillingInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class RebillLessonBookingPayments extends EventHandler
{

    /**
     * @var BillingInterface
     */
    protected $billing;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * Create the event handler.
     *
     * @param  BillingInterface
     * @param  LessonBookingRepositoryInterface $bookings
     * @return void
     */
    public function __construct(
        BillingInterface                 $billing,
        LessonBookingRepositoryInterface $bookings
    ) {
        $this->billing  = $billing;
        $this->bookings = $bookings;
    }

    /**
     * Handle the event.
     *
     * @param  UserCardWasUpdated $event
     * @return void
     */
    public function handle(UserCardWasUpdated $event)
    {
        // Lookups
        $student  = $event->user;
        $customer = $this->billing->account($student);
        $bookings = $this->bookings->getRebillableByStudent($student);
        // Loopy
        foreach ($bookings as $booking) {
            // Refund any charges
            if ($booking->getChargeId()) {
                $charge = $this->billing->charge($customer, $booking);
                $charge->refund();
            }

            $date = $this->getDate();

            // If we're in the capture period, we might as well request
            // a charge (instant payment)
            if ($date->gt($booking->finish_at)) {
                $booking = LessonBooking::recapture($booking);
            } else {
                $booking = LessonBooking::reauthorise($booking);
            }
            // Save
            $this->bookings->save($booking);
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

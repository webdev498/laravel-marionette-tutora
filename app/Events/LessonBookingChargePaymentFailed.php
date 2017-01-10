<?php namespace App\Events;

use App\Events\Event;
use App\LessonBooking;
use Illuminate\Queue\SerializesModels;
use App\Billing\Contracts\Exceptions\BillingExceptionInterface;

class LessonBookingChargePaymentFailed extends Event
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  LessonBooking             $booking
     * @param  BillingExceptionInterface $e
     * @return void
     */
    public function __construct(
        LessonBooking             $booking,
        BillingExceptionInterface $e
    ) {
        $this->booking = $booking;
        $this->e       = $e;
    }

}

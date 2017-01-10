<?php namespace App\Events;

use App\Events\Event;
use App\LessonBooking;
use Illuminate\Queue\SerializesModels;

class LessonBookingWasCancelled extends Event
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  LessonBooking $booking
     * @return void
     */
    public function __construct(LessonBooking $booking)
    {
        $this->booking = $booking;
    }

}

<?php

namespace App\Events;

use App\LessonBooking;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class LessonBookingTransferFailed extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LessonBooking $booking)
    {
        $this->booking = $booking;
    }
}

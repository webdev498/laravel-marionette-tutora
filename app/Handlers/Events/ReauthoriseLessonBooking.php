<?php

namespace App\Handlers\Events;

use App\LessonBooking;
use App\Events\LessonBookingWasEdited;

class ReauthoriseLessonBooking extends AbstractReauthoriseLessonBooking
{

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasEdited $event
     * @return void
     */
    public function handle(LessonBookingWasEdited $event)
    {
        $booking      = $event->booking;
        $lesson       = $booking->lesson;
        $relationship = $lesson->relationship;
        $student      = $relationship->student;

        $this->reauthorise($booking, $student);
    }

}


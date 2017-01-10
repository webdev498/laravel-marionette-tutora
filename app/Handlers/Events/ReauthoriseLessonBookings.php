<?php

namespace App\Handlers\Events;

use App\LessonBooking;
use App\Events\LessonWasEdited;

class ReauthoriseLessonBookings extends AbstractReauthoriseLessonBooking
{

    /**
     * Handle the event.
     *
     * @param  LessonWasEdited $event
     * @return void
     */
    public function handle(LessonWasEdited $event)
    {
        $lesson       = $event->lesson;
        $bookings     = $lesson->bookings;
        $relationship = $lesson->relationship;
        $student      = $relationship->student;

        foreach ($bookings as $booking) {
            $this->reauthorise($booking, $student);
        }
    }

}


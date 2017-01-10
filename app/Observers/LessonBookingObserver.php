<?php namespace App\Observers;

use App\LessonBooking;
use App\Lesson;

class LessonBookingObserver
{

    public function saving(LessonBooking $booking)
    {
        // Finish time
        $booking->finish_at = $booking->start_at
            ->copy()
            ->addSeconds($booking->duration);

        // Price
        $hours = bcdiv($booking->duration, 3600, 2);
        $trial = ($booking->lesson()->first()->type == Lesson::TRIAL);

        $booking->price = $trial ? $booking->rate : bcmul($booking->rate, $hours, 2);
    }

}

<?php namespace App\Commands;

use App\User;
use App\Commands\Command;

class CreateLessonBookingCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $date = null,
        $duration = null,
        $rate = null,
        $location = null,
        $repeat = null,
        $student = [],
        $subject = [],
        $time = null,
        $trial = 0
    ) {
        $this->date     = $date;
        $this->duration = $duration;
        $this->rate     = $rate;
        $this->location = $location;
        $this->repeat   = $repeat;
        $this->student  = $student;
        $this->subject  = $subject;
        $this->time     = $time;
        $this->trial    = $trial;
    }

}

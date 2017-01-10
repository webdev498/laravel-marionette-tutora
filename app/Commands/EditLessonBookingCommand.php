<?php namespace App\Commands;

use App\Commands\Command;
use App\User;

class EditLessonBookingCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $uuid     = null,
        $date     = null,
        $future   = null,
        $location = null,
        $time     = null,
        $duration = null,
        $rate     = null
    ) {
        $this->uuid     = $uuid;
        $this->date     = $date;
        $this->future   = $future;
        $this->location = $location;
        $this->time     = $time;
        $this->duration = $duration;
        $this->rate     = $rate;
    }

}

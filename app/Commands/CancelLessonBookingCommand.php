<?php namespace App\Commands;

use App\Commands\Command;
use App\User;

class CancelLessonBookingCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $uuid,
        $future
    ) {
        $this->uuid   = $uuid;
        $this->future = $future;
    }

}

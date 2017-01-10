<?php namespace App\Commands;

use App\Commands\Command;

class RetryLessonBookingCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $uuid
    ) {
        $this->uuid    = $uuid;
    }

}

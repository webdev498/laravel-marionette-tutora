<?php namespace App\Commands\Jobs;

use App\Commands\Command;

class DeleteJobCommand extends Command
{

    /**
     * Create a new command instance
     *
     * @param string        $uuid
     */
    public function __construct(
        $uuid
    ) {
        $this->uuid = $uuid;
    }

}

<?php namespace App\Commands\Jobs;

use App\Commands\Command;

class GetJobCommand extends Command
{

    /**
     * Get a job command instance.
     *
     * @param string $uuid
     */
    public function __construct(
        $uuid
    ) {
        $this->uuid = $uuid;
    }

}

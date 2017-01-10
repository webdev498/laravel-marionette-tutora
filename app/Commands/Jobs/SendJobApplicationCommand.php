<?php namespace App\Commands\Jobs;

use App\Job;
use App\Relationship;
use App\Commands\Command;

class SendJobApplicationCommand extends Command
{

    /**
     * Create a new command instance
     *
     * @param Relationship $relationship
     * @param Job          $job
     * @param string       $body
     */
    public function __construct(
        $relationship,
        $job,
        $body
    ) {
        $this->relationship = $relationship;
        $this->job          = $job;
        $this->body         = $body;
    }

}

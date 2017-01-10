<?php namespace App\Events\Jobs;

use App\Job;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class JobWasMadePending extends Event
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Job $job
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

}

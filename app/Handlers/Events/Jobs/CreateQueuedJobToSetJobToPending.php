<?php

namespace App\Handlers\Events\Jobs;


use App\Events\Jobs\JobWasCreated;
use App\Handlers\Events\EventHandler;
use App\Jobs\CheckIfJobShouldBeSetToPending;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;


class CreateQueuedJobToSetJobToPending extends EventHandler
{
    use DispatchesJobs;


    /**
     * Handle the event.
     *
     * @param  JobWasCreated  $event
     * @return void
     */
    public function handle(JobWasCreated $event)
    {
        $job = (new CheckIfJobShouldBeSetToPending($event->job))->delay(config('jobs.new_period')*60*60);
        $this->dispatch($job);
    }
}

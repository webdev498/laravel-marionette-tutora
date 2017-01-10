<?php

namespace App\Handlers\Events\Jobs;

use App\Job;
use App\Events\Jobs\JobWasApplied;
use App\Handlers\Events\EventHandler;
use App\Repositories\Contracts\JobRepositoryInterface;

class CloseJobByApplications extends EventHandler
{
    /**
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * Create the event handler.
     *
     * @param JobRepositoryInterface $jobs
     */
    public function __construct(
        JobRepositoryInterface $jobs
    ) {
        $this->jobs = $jobs;
    }

    /**
     * Handle the event.
     *
     * @param  JobWasApplied $event
     *
     * @return void
     */
    public function handle(JobWasApplied $event)
    {
        // Lookups
        $job          = $event->job;
        $appliedCount = $job->getAppliedTutorsCount();

        if($appliedCount < Job::MAX_APPLIES_COUNT) {return;}

        $job = Job::makeClosed($job, Job::CLOSED_FOR_APPLICATIONS);

        $this->dispatchFor($job);

        // Save
        $this->jobs->save($job);
    }

}

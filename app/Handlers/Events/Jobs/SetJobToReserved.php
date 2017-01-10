<?php

namespace App\Handlers\Events\Jobs;

use App\Events\StudentWasMatched;
use App\Handlers\Events\EventHandler;
use App\Job;
use App\Repositories\Contracts\JobRepositoryInterface;

class SetJobToReserved extends EventHandler
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
    public function handle(StudentWasMatched $event)
    {
        // Lookups
        $student          = $event->student;
        $pendingJobs  		  = $student->jobs()->pending()->get();

        if (! $pendingJobs) return;

        foreach ($pendingJobs as $job)
        {               
            $job = Job::makeReserved($job);

            // Dispatch
            $this->dispatchFor($job);

            // Save
            $this->jobs->save($job);
            
        }
    }
}
<?php

namespace App\Handlers\Events\Jobs;

use App\Events\StudentWasMismatched;
use App\Handlers\Events\EventHandler;
use App\Job;
use App\Repositories\Contracts\JobRepositoryInterface;

class SetJobToPending extends EventHandler
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
    public function handle(StudentWasMismatched $event)
    {
        // Lookups
        $student          = $event->student;
        $newJobs  		  = $student->jobs()->new()->get();

        if (! $newJobs) return;

        foreach ($newJobs as $job)
        {                
            $job = Job::makePending($job);

            // Dispatch
            $this->dispatchFor($job);

            // Save
            $this->jobs->save($job);
            
        }
    }
}
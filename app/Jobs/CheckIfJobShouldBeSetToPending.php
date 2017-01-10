<?php

namespace App\Jobs;

use App\Job;
use App\Students\StudentStatusCalculator;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckIfJobShouldBeSetToPending extends AbstractJob implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, Queueable;

    protected $tuitionJob;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Job $tuitionJob)
    {
        $this->tuitionJob = $tuitionJob;
    }

    /**
     * Execute the job.
     * 
     * @return void
     */
    public function handle()
    {
        // Lookups
        $job = $this->tuitionJob;
        $student = $job->user;

        if ($job->status == Job::STATUS_NEW) {
            // Check if student has received a positive response.
            $calc = new StudentStatusCalculator($student);
            
            if (! $calc->hasPositiveResponse()) {
                $job = $this->setJobStatusToPending($job);
            }

            if ($calc->hasPositiveResponse()) {
                $job = $this->setJobStatusToReserved($job);
            }
            $this->dispatchFor($job);
        }

        
    }

    protected function setJobStatusToPending(Job $job)
    {
        $job = Job::makePending($job);
        $job->save();
        return $job;
    }

    protected function setJobStatusToReserved(Job $job)
    {
        $job = Job::makeReserved($job);
        $job->save();
        return $job;    
    }

}

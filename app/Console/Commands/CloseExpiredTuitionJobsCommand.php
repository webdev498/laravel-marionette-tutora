<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Job;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\JobRepositoryInterface;

class CloseExpiredTuitionJobsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:close_expired_tuition_jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close expired tuition jobs.';

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * Create a new command instance.
     *
     * @param  DatabaseManager        $database
     * @param  JobRepositoryInterface $jobs
     */
    public function __construct(
        DatabaseManager        $database,
        JobRepositoryInterface $jobs
    ) {
        parent::__construct();

        $this->database = $database;
        $this->jobs     = $jobs;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        loginfo("[ Background ] {$this->name}");

        $this->database->transaction(function () {
            $date = $this->getDate();
            $jobs = $this->jobs->getOpenedBeforeDate($date);

            foreach ($jobs as $job) {
                Job::makeClosed($job, Job::CLOSED_FOR_EXPIRATION);
                $this->jobs->save($job);

                $this->dispatchFor($job);
            }
        });
    }

    /**
     * Get the date to grab jobs by
     *
     * @return Carbon
     */
    protected function getDate()
    {
        $days = config('jobs.expire_period', 14);

        return Carbon::now()->subDays($days);
    }

}

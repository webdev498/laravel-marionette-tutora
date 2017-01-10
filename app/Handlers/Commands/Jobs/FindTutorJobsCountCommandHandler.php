<?php

namespace App\Handlers\Commands\Jobs;

use App\Auth\Exceptions\UnauthorizedException;
use App\Billing\Contracts\BillingInterface;
use App\Commands\Jobs\FindTutorJobsCountCommand;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Handlers\Commands\CommandHandler;
use App\Job;
use App\Location;
use App\Pagination\JobsPaginator;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Search\JobSearcher;
use App\Search\JobsResult;
use App\Tutor;
use App\User;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Support\Collection;
use App\TuitionJobs\JobEligibilityCalculator;

class FindTutorJobsCountCommandHandler extends CommandHandler
{
    /**
     * An array of objects to dispatch events off.
     *
     * @var array
     */
    protected $dispatch = [];

    /**
     * @var Database
     */
    protected $database;

    /**
     * The Guard implementation.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * @var JobSearcher
     */
    protected $searcher;

    /**
     * @var JobsPaginator
     */
    protected $paginator;

    /**
     * @var JobEligibilityCalculator
     */
    protected $calc;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                 $database
     * @param  Auth                     $auth
     * @param  JobSearcher              $searcher
     * @param  JobEligibilityCalculator $calc
     */
    public function __construct(
        Database                    $database,
        Auth                        $auth,
        JobSearcher                 $searcher,
        JobEligibilityCalculator    $calc
    ) {
        $this->database = $database;
        $this->auth     = $auth;
        $this->searcher = $searcher;
        $this->calc     = $calc;
    }

    /**
     * Execute the command.
     *
     * @param  FindTutorJobsCountCommand $command
     *
     * @return Job
     */
    public function handle(FindTutorJobsCountCommand $command)
    {
        // Guard
        $currentUser = $this->auth->user();
        $this->guardAgainstUserNotAllowedToGet($currentUser);

        if(!$this->calc->profileScoreIsEligible($command->tutor->profile)) {
            return 0;
        }

        $jobsCount = $this->findJobsCount($command);

        // Return
        return $jobsCount;
    }

    /**
     * Guard against a user not having permission to get a user.
     *
     * @param  User $user
     * @return void
     *
     * @throws UnauthorizedException
     */
    protected function guardAgainstUserNotAllowedToGet(User $user)
    {
        $isTutor = $user->roles();

        if (!$user->isAdmin() && !$isTutor) {
            throw new UnauthorizedException();
        }
    }

    /**
     * @param FindTutorJobsCommand $command
     *
     * @return mixed
     */
    protected function findJobsCount(FindTutorJobsCountCommand $command)
    {
        $tutor    = $command->tutor;
        $location = $command->location;

        list($items, $count) = $this->searcher->search(
            1,
            1,
            null,
            null,
            $tutor,
            $location
        );

        return $count;
    }
}

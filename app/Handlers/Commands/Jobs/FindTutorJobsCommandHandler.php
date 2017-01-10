<?php

namespace App\Handlers\Commands\Jobs;

use App\Location;
use App\User;
use App\Tutor;
use App\Job;
use App\Search\JobsResult;
use App\Search\JobSearcher;
use App\Pagination\JobsPaginator;
use App\Commands\Jobs\FindTutorJobsCommand;
use App\Handlers\Commands\CommandHandler;
use App\Billing\Contracts\BillingInterface;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Auth\Exceptions\UnauthorizedException;
use Illuminate\Support\Collection;
use App\TuitionJobs\JobEligibilityCalculator;

class FindTutorJobsCommandHandler extends CommandHandler
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
     * @param  JobsPaginator            $paginator
     * @param  JobEligibilityCalculator $calc
     */
    public function __construct(
        Database                    $database,
        Auth                        $auth,
        JobSearcher                 $searcher,
        JobsPaginator               $paginator,
        JobEligibilityCalculator    $calc
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->searcher  = $searcher;
        $this->paginator = $paginator;
        $this->calc      = $calc;
    }

    /**
     * Execute the command.
     *
     * @param  FindTutorJobsCommand $command
     *
     * @return Job
     */
    public function handle(FindTutorJobsCommand $command)
    {
        // Guard
        $currentUser = $this->auth->user();
        $this->guardAgainstUserNotAllowedToGet($currentUser);

        $jobs = $this->findJobs($command);

        // Dispatch
        $this->dispatchFor($this->dispatch);

        // Return
        return new JobsResult($command, $jobs);
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
    protected function findJobs(FindTutorJobsCommand $command)
    {
        $page    = (int) ($command->page ?: 1);
        $perPage = JobsPaginator::PER_PAGE;
        $sort    = $command->sort ?: JobSearcher::SORT_CLOSEST;
        $filter  = $command->filter ?: JobSearcher::FILTER_SUBJECTS;

        $tutor    = $command->tutor;
        $location = $command->location;

        if(!$this->calc->profileScoreIsEligible($command->tutor->profile)) {
            $items = [];
            $count = 0;
        } else {
            list($items, $count) = $this->searcher->search(
                $page,
                $perPage,
                $sort,
                $filter,
                $tutor,
                $location
            );
        }

        $path = relroute('tutor.jobs.index');

        return $this->paginator->paginate($items, $count, $page, [
            'path' => $path,
            'query' => [
                'sort'   => $sort,
                'filter' => $filter,
            ],
        ]);
    }

    /**
     * Find a job by a given uuid
     *
     * @throws ResourceNotFoundException
     * @param  string $uuid
     *
     * @return Job
     */
    protected function findJob($uuid)
    {
        $job = $this->jobs->findByUuid($uuid);

        if (!$job) {
            throw new ResourceNotFoundException();
        }

        return $job;
    }

}

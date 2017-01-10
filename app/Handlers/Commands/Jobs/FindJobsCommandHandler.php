<?php

namespace App\Handlers\Commands\Jobs;

use App\Location;
use App\User;
use App\Tutor;
use App\Job;
use App\Search\JobsResult;
use App\Search\JobSearcher;
use App\Pagination\JobsPaginator;
use App\Commands\Jobs\FindJobsCommand;
use App\Handlers\Commands\CommandHandler;
use App\Billing\Contracts\BillingInterface;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Auth\Exceptions\UnauthorizedException;
use Illuminate\Support\Collection;

class FindJobsCommandHandler extends CommandHandler
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
     * Create an instance of the handler.
     *
     * @param  Database      $database
     * @param  Auth          $auth
     * @param  JobSearcher   $searcher
     * @param  JobsPaginator $paginator
     */
    public function __construct(
        Database      $database,
        Auth          $auth,
        JobSearcher   $searcher,
        JobsPaginator $paginator
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->searcher  = $searcher;
        $this->paginator = $paginator;
    }

    /**
     * Execute the command.
     *
     * @param  FindJobsCommand $command
     *
     * @return Job
     */
    public function handle(FindJobsCommand $command)
    {
        // Guard
        $currentUser = $this->auth->user();
        $this->guardAgainstUserNotAllowedToGet($currentUser);

        $subjects = $command->subjects;
        $location = $command->location;
        $jobs     = $this->findJobs($command, $subjects, $location);

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
     * @param FindJobsCommand $command
     * @param Collection $subjects
     * @param Location $location
     *
     * @return mixed
     */
    protected function findJobs(FindJobsCommand $command, Collection $subjects, Location $location)
    {
        $page    = (int) ($command->page ?: 1);
        $perPage = JobsPaginator::PER_PAGE;
        $sort    = $command->sort ?: JobSearcher::SORT_CLOSEST;

        list($items, $count) = $this->searcher->search(
            $page,
            $perPage,
            $sort,
            $subjects,
            $location
        );

        $path = relroute('tutor.jobs.index');

        return $this->paginator->paginate($items, $count, $page, [
            'path' => $path,
            'query' => [
                'sort' => $sort,
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

<?php

namespace App\Handlers\Commands\Jobs;

use App\User;
use App\Tutor;
use App\Job;
use App\Address;
use App\Location;
use App\Commands\Jobs\GetJobCommand;
use App\Handlers\Commands\CommandHandler;
use App\Billing\Contracts\BillingInterface;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Auth\Exceptions\UnauthorizedException;

class GetJobCommandHandler extends CommandHandler
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
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                $database
     * @param  Auth                    $auth
     * @param  JobRepositoryInterface  $jobs
     */
    public function __construct(
        Database                $database,
        Auth                    $auth,
        JobRepositoryInterface  $jobs
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->jobs      = $jobs;
    }

    /**
     * Execute the command.
     *
     * @param  GetJobCommand $command
     *
     * @return Job
     */
    public function handle(GetJobCommand $command)
    {
        // Guard
        $currentUser = $this->auth->user();

        $isTutor     = $currentUser->isTutor();

        $this->guardAgainstUserNotAllowedToGet($currentUser);

        $job = $this->findJob($command->uuid);

        if($isTutor) {
            $job = $this->calculateDistance($currentUser, $job);
        }

        $this->dispatch[] = $job;

        // Dispatch
        $this->dispatchFor($this->dispatch);

        // Return
        return $job;
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

    /**
     * @param Tutor $tutor
     * @param Job   $job
     *
     * @return Job
     */
    protected function calculateDistance(Tutor $tutor, Job $job)
    {
        $address       = $tutor->addresses()->where('name', Address::NORMAL)->first();

        $tutorLocation = Location::makeFromAddress($address);

        $jobLocation   = $job->locations()->first();

        $distance = Location::distance($tutorLocation, $jobLocation);

        $job->distance = $distance;

        return $job;
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
        if (!$user->isAdmin() && !$user->isTutor()) {
            throw new UnauthorizedException();
        }
    }

}

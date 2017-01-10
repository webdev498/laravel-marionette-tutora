<?php namespace App\Handlers\Commands\Jobs;

use App\Admin;
use App\Tutor;
use App\User;
use App\Location;
use App\Job;
use App\Search;
use App\Student;
use App\Search\Results;
use App\Search\LocationSearcher;
use App\Search\SubjectSearcher;
use App\Validators\JobValidator;
use App\Commands\Jobs\UpdateJobCommand;
use App\Handlers\Commands\CommandHandler;
use Illuminate\Support\Collection;
use Illuminate\Auth\AuthManager as Auth;
use App\Geocode\Location as GeocodeLocation;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Contracts\Validation\UnauthorizedException;
use App\Repositories\Contracts\SearchRepositoryInterface;
use App\Repositories\Contracts\LocationRepositoryInterface;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class UpdateJobCommandHandler extends CommandHandler
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
     * @var JobValidator
     */
    protected $validator;

    /**
     * @var SubjectSearcher
     */
    protected $subjectSearcher;

    /**
     * @var LocationSearcher
     */
    protected $locationSearcher;
    
    /**
     * @var SearchRepositoryInterface
     */
    protected $searches;

    /**
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var Auth
     */
    protected $auth;
    
    /**
     * Create a new handler instance.
     *
     * @param  Database                    $database
     * @param  JobValidator                $validator
     * @param  SubjectSearcher             $subjectSearcher
     * @param  LocationSearcher            $locationSearcher
     * @param  SearchRepositoryInterface   $searches
     * @param  LocationRepositoryInterface $locations
     * @param  UserRepositoryInterface     $users
     * @param  JobRepositoryInterface      $jobs
     * @param  Auth                        $auth
     */
    public function __construct(
        Database                    $database,
        JobValidator                $validator,
        SubjectSearcher             $subjectSearcher,
        LocationSearcher            $locationSearcher,
        SearchRepositoryInterface   $searches,
        LocationRepositoryInterface $locations,
        UserRepositoryInterface     $users,
        JobRepositoryInterface      $jobs,
        Auth                        $auth
    ) {
        $this->database         = $database;
        $this->validator        = $validator;
        $this->subjectSearcher  = $subjectSearcher;
        $this->locationSearcher = $locationSearcher;
        $this->searches         = $searches;
        $this->locations        = $locations;
        $this->users            = $users;
        $this->jobs             = $jobs;
        $this->auth             = $auth;
    }

    /**
     * Execute the command.
     *
     * @param  UpdateJobCommand $command
     *
     * @return Results
     */
    public function handle(UpdateJobCommand $command)
    {
        return $this->database->transaction(function () use ($command) {

            $this->guardAgainstInvalidData($command);

            $job     = $this->jobs->findByUuid($command->uuid);
            $student = $job->user;

            $this->guardAgainstUnauthorized($student);

            $job = $this->updateJob($job, $command);

            $this->dispatchFor($this->dispatch);

            return $job;
        });
    }

    /**
     * @param string $postcode
     *
     * @return Location
     */
    protected function getLocation($postcode)
    {
        $location = $this->locations->getByPostcode($postcode);

        if(!$location) {
            $locationParams = $this->locationSearcher->search($postcode);
            $location = $this->createLocation($locationParams);
        }

        return $location;
    }

    /**
     * @param GeocodeLocation $locationParams
     *
     * @return Location
     */
    protected function createLocation(GeocodeLocation $locationParams)
    {
        $location = Location::make($locationParams);

        $this->locations->save($location);

        return $location;
    }

    /**
     * @param Job              $job
     * @param UpdateJobCommand $command
     *
     * @return Job
     */
    protected function updateJob(Job $job, UpdateJobCommand $command)
    {
        $params       = [
            'message'    => $command->message,
            'closed_for' => $command->closed_for,
        ];

        $job->fill($params);

        $subjects = $this->subjectSearcher->search($command->subject_name);
        $subject = $subjects ? $subjects->first() : null;
        $prevSubject = $job->subject;
        if(!$prevSubject || ($subject && $prevSubject->id != $subject->id)) {
            $job->subject()->associate($subject);
        }

        $location = $this->getLocation($command->location_postcode);
        if($location && $job->locations()->first()->id != $location->id) {
            $job->locations()->detach($job->location);
            $job->locations()->attach($location);
        }

        if($command->status) {
            $this->setStatus($job, $command->status);
        }

        if ($command->by_request) {
            $job->by_request = 1;
        } else {
            $job->by_request = null;
        }

        if($command->messageLine) {
            $job->messageLines()->attach($command->messageLine);
        }

        $this->jobs->save($job);

        // Dispatch
        $this->dispatch[] = $job;
        $this->dispatch[] = $location;

        return $job;
    }

    /**
     * @param Job $job
     * @param int $status
     *
     * @return Job
     */
    protected function setStatus(Job $job, $status)
    {
        switch ($status) {
            case Job::STATUS_PENDING:
                $job = Job::makePending($job);
                break;

            case Job::STATUS_LIVE:
                $job = Job::makeLive($job);
                break;

            case Job::STATUS_CLOSED:
                $job = Job::makeClosed($job);
                break;

            case Job::STATUS_RESERVED:
                $job = Job::makeReserved($job);
                break;
        }

        return $job;
    }

    /**
     * Guard against invalid data on the command
     *
     * @throws ValidationException
     * @return void
     */
    protected function guardAgainstInvalidData($command)
    {
        $this->validator->validate((array) $command);
    }

    /**
     * Guard against unauthorised request
     *
     * @throws UnauthorizedException
     * @param  User $user
     *
     * @return void
     */
    protected function guardAgainstUnauthorized(User $user)
    {
        $authed = $this->auth->user();

        $isAdmin     = $authed->isAdmin();
        $isSelfEdit  = $authed->id === $user->id;

        if (!$isSelfEdit && !$isAdmin) {
            throw new UnauthorizedException();
        }
    }

    /**
     * Check if auth admin
     *
     * @return boolean
     */
    protected function isAdmin()
    {
        $authed = $this->auth->user();

        return $authed->isAdmin();
    }

}

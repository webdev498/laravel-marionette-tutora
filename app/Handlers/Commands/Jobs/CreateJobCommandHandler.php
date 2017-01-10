<?php namespace App\Handlers\Commands\Jobs;

use App\Admin;
use App\Tutor;
use App\User;
use App\Location;
use App\Subject;
use App\Job;
use App\Search;
use App\Student;
use App\Search\Results;
use App\Search\LocationSearcher;
use App\Search\SubjectSearcher;
use App\Validators\JobValidator;
use App\Commands\Jobs\CreateJobCommand;
use Illuminate\Support\Collection;
use App\Handlers\Commands\CommandHandler;
use Illuminate\Auth\AuthManager as Auth;
use App\Geocode\Location as GeocodeLocation;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Contracts\Validation\UnauthorizedException;
use App\Search\Exceptions\SubjectNotFoundException;
use App\Repositories\Contracts\SearchRepositoryInterface;
use App\Repositories\Contracts\LocationRepositoryInterface;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Exceptions\Jobs\DuplicateJobException;

class CreateJobCommandHandler extends CommandHandler
{

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
     * @param  CreateJobCommand $command
     * @return Results
     */
    public function handle(CreateJobCommand $command)
    {
        return $this->database->transaction(function () use ($command) {

            $this->guardAgainstInvalidData($command);

            $user   = $this->getUser($command);
            $tutor  = $this->getTutor($command);

            $this->guardAgainstUnauthorized($user);

            $location   = $this->getLocation($command->location_postcode);
            $subject    = $this->getSubject($command);
            $jobParams  = $this->getNewJobParams($command);
            $job = $this->createJob($jobParams, $user, $subject, $location, $tutor);
            if ($command->by_request) {
                $job->by_request = 1;
            } else {
                $job->by_request = null;
            }

            if($command->messageLine) {
                $job->messageLines()->attach($command->messageLine);
            }

            if($tutor) {
                $this->markJobCreatedForTutor($job, $tutor);
            }

            $this->dispatchFor(array_filter([$location, $job]));

            return $job;
        });
    }

    /**
     * @param CreateJobCommand $command
     *
     * @return Subject|null
     */
    protected function getSubject(CreateJobCommand $command)
    {
        try {
            $subjects = $this->subjectSearcher->search($command->subject_name);
        } catch (SubjectNotFoundException $e) {
            $subjects = null;
        }

        /** @var Subject */
        $subject = $subjects ? $subjects->first() : null;

        return $subject;
    }

    /**
     * @param CreateJobCommand $command
     *
     * @return array
     */
    protected function getNewJobParams(CreateJobCommand $command)
    {
        $message = $command->subject_name . "\n" . $command->message;

        $jobParams       = [
            'message' => $message,
            'by_request' => $command->by_request
        ];

        return $jobParams;
    }

    /**
     * @param CreateJobCommand $command
     *
     * @return User|null
     */
    protected function getTutor(CreateJobCommand $command)
    {
        $tutor = null;
        if($command->tutor) {
            $tutor = $this->users->findByUuid([$command->tutor]);
        }

        return $tutor;
    }

    /**
     * @param CreateJobCommand $command
     *
     * @return Student|User|null
     */
    protected function getUser(CreateJobCommand $command)
    {
        if($command->studentUuid) {
            $user = $this->users->findByUuid($command->studentUuid);
        } else {
            $user = $command->student;
        }

        return $user;
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
     * @param array      $params
     * @param Student    $student
     * @param Subject    $subject
     * @param Location   $location
     * @param Tutor      $tutor
     *
     * @return Job
     */

    protected function createJob($params, Student $student, Subject $subject = null, Location $location, Tutor $tutor = null)
    {
        $this->guardAgainstDuplicateJob($student, $tutor, $subject, $location->postcode);

        $job = Job::make($params, $student, $subject, $tutor);

        if($this->isAdmin()) {
            Job::makeLive($job);
        }

        if(is_null($tutor)) {
            Job::makePending($job);
        }

        $this->jobs->save($job);

        $job->locations()->attach($location);

        return $job;
    }

    /**
     * @param Job    $job
     * @param Tutor  $tutor
     *
     * @return Job
     */
    protected function markJobCreatedForTutor(Job $job, Tutor $tutor)
    {
        $tutorJob = $this->getTutorJob($job, $tutor);

        $tutorJob->pivot->created_for_tutor = true;
        $tutorJob->pivot->save();

        return $tutorJob;
    }

    /**
     * @param Job   $job
     * @param Tutor $tutor
     *
     * @return Job
     */
    protected function getTutorJob(Job $job, Tutor $tutor)
    {
        $tutorJob = $tutor->jobs()->find($job->id);

        if(!$tutorJob) {
            $tutor->jobs()->attach($job);
            $tutorJob = $tutor->jobs()->find($job->id);
        }

        return $tutorJob;
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
     * Guard against duplicate job request
     *
     * @throws DuplicateJobException
     *
     * @param  Student  $student
     * @param  Tutor    $tutor
     * @param  Subject  $subject
     * @param  $postcode
     *
     * @return void
     */
    protected function guardAgainstDuplicateJob(Student $student, Tutor $tutor = null, Subject $subject = null, $postcode)
    {
        $relationshipExists = false;
        if($tutor) {
            $relationshipExists = $this->studentHasRelationshipWithTutor($student, $tutor);
        }
        $similarJobExists = $this->checkSimilarJobExists($student, $subject, $postcode);

        if ($similarJobExists || $relationshipExists) {
            throw new DuplicateJobException('Similar job already exists!');
        }
    }

    /**
     * @param  Student  $student
     * @param  Tutor    $tutor
     *
     * @return bool
     */
    protected function studentHasRelationshipWithTutor(Student $student, Tutor $tutor)
    {
        $query = $student->relationships()
            ->where('tutor_id', $tutor->id);

        $count  = $query->count();

        return $count > 0;
    }

    /**
     * @param Student $student
     * @param Subject|null $subject
     * @param $postcode
     *
     * @return bool
     */
    protected function checkSimilarJobExists(Student $student, Subject $subject = null, $postcode)
    {
        if(!$subject) { return false; }

        $query = $student->jobs();

        $subjectSiblings = $subject->getSiblings()->lists('id');
        $subjectSiblings->add($subject->id);
        $subjectSiblings->add($subject->getParentId());

        $query = $query->whereIn('subject_id', $subjectSiblings);

        $query = $query->where(function($query) {
            $query->where('status', '=', Job::STATUS_LIVE)
                ->orWhere('status', '=', Job::STATUS_PENDING)
                ->orWhere('status', '=', Job::STATUS_NEW);
        });

        $jobsCount = $query->count();

        return $jobsCount > 0;
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

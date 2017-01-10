<?php namespace App\Http\Controllers\Api;

use App\Auth\Exceptions\AuthException;
use App\Auth\Exceptions\UnauthorizedException;
use App\Database\Exceptions\DuplicateResourceException;
use App\Geocode\Exceptions\NoResultException;

use App\Commands\Jobs\GetJobCommand;
use App\Commands\Jobs\FindJobsCommand;
use App\Commands\Jobs\CreateJobCommand;
use App\Commands\Jobs\UpdateJobCommand;
use App\Commands\Jobs\DeleteJobCommand;
use App\Commands\Jobs\FavouriteJobCommand;
use App\Commands\Jobs\SendJobApplicationCommand;
use App\Commands\RegisterUserCommand;
use App\Commands\FormRelationshipCommand;

use App\Student;
use App\Tutor;
use App\Job;
use App\User;
use App\Transformers\JobTransformer;
use App\Transformers\MessageLineTransformer;
use App\Validators\Exceptions\ValidationException;
use App\Exceptions\Jobs\DuplicateJobException;
use App\Exceptions\Jobs\ClosedJobException;

use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Http\Request;
use Lord\Laroute\Routes\Collection;

use App\Repositories\Contracts\JobRepositoryInterface;

class JobsController extends ApiController
{

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var JobRepositoryInterface
     */
    protected $jobs;
    
    /**
     * Create an instance of this
     *
     * @param  Database               $database
     * @param  Auth                   $auth
     * @param  JobRepositoryInterface $jobs
     */
    public function __construct(
        Database               $database,
        Auth                   $auth,
        JobRepositoryInterface $jobs
    ) {
        $this->database = $database;
        $this->auth     = $auth;
        $this->jobs     = $jobs;
    }

    /**
     * Create job request
     *
     * @param  Request $request
     *
     * @return mixed
     */
    public function create(Request $request)
    {
        try {
            return $this->database->transaction(function () use ($request) {

                $user = $this->auth->user();

                $isStudent = $user instanceof Student;
                $isAdmin = $user->isAdmin();

                if (!$isStudent && !$isAdmin) {
                    throw new UnauthorizedException();
                }

                if($isAdmin) {
                    $student = $this->dispatchFrom(RegisterUserCommand::class, $request, [
                        'account' => 'student',
                        'status'  => 'confirmed',
                    ]);
                } else {
                    $student = $user;
                }

                //create job
                $job = $this->dispatchFrom(CreateJobCommand::class, $request, [
                    'student'      => $student,
                ]);

                return $this->respondWithCreatedItem(
                    $job,
                    new JobTransformer()
                );
            });
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            return $this->respondWithBadRequest($e->getMessage(), $errors);
        } catch (DuplicateJobException $e) {
            return $this->respondWithBadRequest($e->getMessage());
        } catch (AuthException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (DuplicateResourceException $e) {
            return $this->respondWithConflict('ERR_INVALID', $e->getErrors());
        }
    }

    /**
     * Update job request
     *
     * @param Request $request
     * @param string  $uuid
     *
     * @return mixed
     */
    public function update(Request $request, $uuid)
    {
        try {
            return $this->database->transaction(function () use ($request, $uuid) {

                //update job
                $job = $this->dispatchFrom(UpdateJobCommand::class, $request, [
                    'uuid' => $uuid,
                ]);

                return $this->returnTransformedJob($job);
            });
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            return $this->respondWithBadRequest($e->getMessage(), $errors);
        } catch (AuthException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

    /**
     * Delete job request
     *
     * @param Request $request
     * @param string  $uuid
     *
     * @return mixed
     */
    public function delete(Request $request, $uuid)
    {
        try {
            return $this->database->transaction(function () use ($request, $uuid) {

                //Delete job
                $this->dispatchFrom(DeleteJobCommand::class, $request, [
                    'uuid' => $uuid,
                ]);

                return $this->respondWithArray([
                    'success' => true,
                    'uuid' => $uuid
                ], 200);
            });
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (AuthException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

    /**
     * Favourite a job request
     *
     * @param Request $request
     * @param string  $uuid
     *
     * @return mixed
     */
    public function favourite(Request $request, $uuid)
    {
        try {
            return $this->database->transaction(function () use ($request, $uuid) {

                $user = $this->auth->user();

                //update job
                $job = $this->dispatchFrom(FavouriteJobCommand::class, $request, [
                    'uuid' => $uuid,
                    'user' => $user,
                ]);

                return $this->returnTransformedJob($job, [
                    'tutor',
                ],
                    $user);
            });
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (AuthException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

    /**
     * Create a message request
     *
     * @param Request $request
     * @param string  $uuid
     *
     * @return mixed
     */
    public function createMessage(Request $request, $uuid)
    {

        try {
            return $this->database->transaction(function () use ($request, $uuid) {

                $user = $this->auth->user();

                if ( ! ($user->isTutor())) {
                    throw new UnauthorizedException();
                }

                $job = $this->jobs->findByUuid($uuid);
                $student = $job->user;

                //Create relationship
                $relationship = $this->dispatchFromArray(FormRelationshipCommand::class, [
                    'student' => $student,
                    'tutor'   => $user,
                ]);

                //Send application
                list($message, $line) = $this->dispatchFrom(SendJobApplicationCommand::class, $request, [
                    'relationship' => $relationship,
                    'job' => $job,
                ]);

                return $this->respondWithCreatedItem(
                    $line,
                    new MessageLineTransformer()
                );
            });
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            return $this->respondWithBadRequest($e->getMessage(), $errors);
        } catch (ClosedJobException $e) {
            return $this->respondWithBadRequest($e->getMessage());
        } catch (AuthException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (DuplicateResourceException $e) {
            $errors = $e->getErrors();
            return $this->respondWithConflict('ERR_INVALID', $errors);
        }
    }

    /**
     * Get an existing job
     *
     * @param  Request $request
     * @param  string  $uuid
     *
     * @return mixed
     */
    public function get(Request $request, $uuid)
    {
        try {

            $job = $this->dispatchFrom(GetJobCommand::class, $request, [
                'uuid' => $uuid,
            ]);

            $user = $this->auth->user();

            if($user->isAdmin()) {
                $transformed = $this->returnTransformedJobForAdmin($job);
            } elseif ($user->isTutor()) {
                $transformed = $this->returnTransformedJobForTutor($job, $user);
            } else {
                $transformed = $this->returnTransformedJob($job);
            }

            return $transformed;
        } catch (NoResultException $e) {
            return $this->respondWithBadRequest($e->getMessage());
        }
    }

    /**
     * Get jobs
     *
     * @param  Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $jobs = $this->dispatchFrom(FindJobsCommand::class, $request);

            return $this->returnTransformedJobs($jobs);
        } catch (NoResultException $e) {
            return $this->respondWithBadRequest($e->getMessage());
        }
    }

    /**
     * @param Collection $jobs
     *
     * @return Array|Object
     */
    private function returnTransformedJobs(Collection $jobs)
    {
        return $this->transformCollection($jobs, new JobTransformer(), [
            'include' => [
                'location',
                'student',
                'subject',
            ],
        ]);
    }

    /**
     * @param Job   $job
     *
     * @return Array|Object
     */
    private function returnTransformedJobForAdmin(Job $job)
    {
        return $this->returnTransformedJob($job, [
            'location',
            'student',
            'subject',
            'replies',
            'initialTutorMessage',
            'by_request'
        ]);
    }

    /**
     * @param Job   $job
     * @param Tutor $tutor
     *
     * @return Array|Object
     */
    private function returnTransformedJobForTutor(Job $job, Tutor $tutor)
    {
        $includes = [
            'location',
            'student',
            'subject',
            'tutor'
        ];

        return $this->returnTransformedJob($job, $includes, $tutor);
    }

    /**
     * @param Job   $job
     * @param array $include
     * @param User  $tutor
     *
     * @return Array|Object
     */
    private function returnTransformedJob(Job $job, $include = [], User $tutor = null)
    {
        $include = $include ?: [
            'location',
            'student',
            'subject',
        ];

        return $this->transformItem($job, new JobTransformer([
            'tutor' => $tutor,
            'auth'  => $this->auth->user(),
        ]), [
            'include' => $include,
        ]);
    }

}

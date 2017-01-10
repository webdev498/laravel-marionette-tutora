<?php namespace App\Http\Controllers\Api;

use App\Address;
use App\Auth\Exceptions\UnauthorizedException;
use App\Billing\Contracts\Exceptions\CardExceptionInterface;

use App\Commands\DeleteStudentCommand;
use App\Commands\DeleteTutorCommand;
use App\Commands\RegisterUserCommand;
use App\Commands\ToggleBlockUserCommand;
use App\Commands\UpdateUserCommand;
use App\Commands\GetUserCommand;
use App\Commands\Jobs\CreateJobCommand;

use App\Database\Exceptions\DuplicateResourceException;
use App\Geocode\Exceptions\NoResultException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Student;
use App\Transformers\StudentTransformer;
use App\Transformers\TutorTransformer;
use App\Tutor;
use App\Validators\Exceptions\ValidationException;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Http\Request;

use Illuminate\Database\DatabaseManager as Database;

class UsersController extends ApiController
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
     * Create an instance of this
     *
     * @param  Database $database
     * @param  Auth     $auth
     */
    public function __construct(
        Database $database,
        Auth     $auth
    )
    {
        $this->database = $database;
        $this->auth     = $auth;
    }

    /**
     * Create a new user
     *
     * @param  Request $request
     * @return mixed
     */
    public function create(Request $request)
    {

        try {
            return $this->database->transaction(function () use ($request) {
                $user = $this->dispatchFrom(RegisterUserCommand::class, $request);

                $this->auth->login($user);

                if ($user instanceof Tutor) {
                    return $this->respondWithCreatedItem($user, new TutorTransformer(), [
                        'meta' => [
                            'redirect' => relroute('tutor.profile.show', ['uuid' => $user->uuid]),
                        ]
                    ]);
                }

                if ($user instanceof Student) {

                    $job = null;
                    if ($request->get('subject') || $request->get('location') || $request->get('message')) {
                        $job = $this->dispatchFrom(CreateJobCommand::class, $request, [
                            'student' => $user,
                        ]);
                    }

                    if($job) {
                        $redirect = relroute('student.dashboard.index', ['dialogue' => 'student_job_created']);
                    } else {
                        $redirect = relroute('student.dashboard.index');
                    }

                    return $this->respondWithCreatedItem($user, new StudentTransformer(), [
                        'meta' => [
                            'redirect' => $redirect,
                        ],
                    ]);
                }

                return null;
            });
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (DuplicateResourceException $e) {
            return $this->respondWithConflict('ERR_INVALID', $e->getErrors());
        }
    }

    /**
     * Update an existing user
     *
     * @param  Request $request
     * @param  string  $uuid
     * @return mixed
     */
    public function edit(Request $request, $uuid)
    {
        try {
            $user = $this->dispatchFrom(UpdateUserCommand::class, $request, [
                'uuid' => $uuid,
            ]);

            if ($user instanceof Tutor) {
                return $this->transformItem($user, new TutorTransformer(), [
                    'include' => [
                        'private',
                        'profile',
                        'addresses',
                        'subjects',
                        'qualifications',
                        'identity_document',
                        'background_checks',
                    ],
                ]);
            }

            return $this->transformItem($user, new StudentTransformer(), [
                'include' => [
                    'private',
                    'addresses',
                ],
            ]);
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (CardExceptionInterface $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (NoResultException $e) {
            return $this->respondWithBadRequest($e->getMessage());
        } catch (DuplicateResourceException $e) {
            return $this->respondWithConflict('ERR_INVALID', $e->getErrors());
        }
    }

    /**
     * Get an existing user
     *
     * @param  Request $request
     * @param  string  $uuid
     * @return mixed
     */
    public function get(Request $request, $uuid)
    {
        try {
            $user = $this->dispatchFrom(GetUserCommand::class, $request, [
                'uuid' => $uuid,
            ]);

            //\Log::info($user);

            if ($user instanceof Tutor) {
                return $this->transformItem($user, new TutorTransformer(), [
                    'include' => [
                        'private',
                        'profile',
                        'addresses',
                        'subjects',
                        'students',
                        'qualifications',
                        'identity_document',
                        'background_checks',
                    ],
                ]);
            }

            return $this->transformItem($user, new StudentTransformer(), [
                'include' => [
                    'private',
                    'addresses',
                ],
            ]);
        } catch (NoResultException $e) {
            return $this->respondWithBadRequest($e->getMessage());
        }
    }

    public function destroy(UserRepositoryInterface $users, $uuid)
    {
        try {
            $user = $users->findByUuid($uuid);
            if ($user->isTutor()) {
                $this->dispatch(new DeleteTutorCommand($uuid));
            } elseif ($user->isStudent()) {
                $this->dispatch(new DeleteStudentCommand($uuid));
            } else {
                throw new \Exception('You cannot delete this user');
            }

            return $this->respondWithArray([
                'success' => true,
                'uuid' => $uuid
            ], 200);

        } catch (UnauthorizedException $e) {
            return $this->respondWithBadRequest('You do not have permissions to delete this user');
        } catch (\Exception $e) {
            return $this->respondWithBadRequest($e->getMessage());
        }
    }

    public function toggleBlock(Request $request, $uuid) {
        try {
            $user = $this->dispatchFrom(ToggleBlockUserCommand::class, $request, [
                'uuid' => $uuid,
            ]);

            if ($user instanceof Tutor) {
                return $this->transformItem($user, new TutorTransformer(), [
                    'include' => [
                        'private',
                        'profile',
                        'addresses',
                        'subjects',
                        'qualifications',
                        'identity_document',
                        'background_checks'
                    ],
                ]);
            }

            return $this->transformItem($user, new StudentTransformer(), [
                'include' => [
                    'private',
                    'addresses',
                ],
            ]);
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (CardExceptionInterface $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (NoResultException $e) {
            return $this->respondWithBadRequest($e->getMessage());
        } catch (DuplicateResourceException $e) {
            return $this->respondWithConflict('ERR_INVALID', $e->getErrors());
        }
    }

}

<?php namespace App\Http\Controllers\Api;

use App\Auth\Exceptions\AuthException;
use App\Commands\FormRelationshipCommand;
use App\Commands\LoginCommand;
use App\Commands\RegisterUserCommand;
use App\Commands\SendMessageCommand;
use App\Commands\Jobs\CreateJobCommand;
use App\Database\Exceptions\DuplicateResourceException;
use App\Repositories\Contracts\SearchRepositoryInterface;
use App\SearchQuery;
use App\Student;
use App\Transformers\MessageTransformer;
use App\Tutor;
use App\Validators\Exceptions\ValidationException;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Exceptions\Jobs\DuplicateJobException;

class MessagesController extends ApiController
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
     * @var Search
     */
    protected $search;

    /**
     * @var Student
     */
    protected $student;

    /**
     * Create an instance of this
     *
     * @param  Database                 $database
     * @param  Auth                     $auth
     *
     * @param SearchRepositoryInterface $search
     * @param Student                   $student
     */
    public function __construct(
        Database $database,
        Auth $auth,
        SearchRepositoryInterface $search,
        Student $student
    ) {
        $this->database = $database;
        $this->auth     = $auth;
        $this->search   = $search;
        $this->student  = $student;
    }

    /**
     * Create a relationship between a student and a tutor. Open up a message,
     * and potentially register/login the student
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function create(
        Request $request
    ) {
        $search = $this->search->getById(session('search_id'));

        $action = $request->get('action', 'none');
        try {
            return $this->database->transaction(function () use ($request, $action, $search) {
                switch ($action) {
                    case 'register':
                        $data = $request->get('register', []);

                        /*
                         * Check if the student's email exists
                         * */
                        if ($this->student->where('email', $data['email'])->get()->first()) {
                            return new JsonResponse([
                                'meta'  => [
                                    'redirect' => true
                                ],
                                'email' => $data['email']
                            ], 200);
                        }

                        $user = $this->dispatchFromArray(
                            RegisterUserCommand::class,
                            array_extend($data, [
                                'account' => 'student',
                            ])
                        );

                        $createJob = $data['create_job'];

                        $this->auth->login($user);
                        //attach search to user.
                        if ($search) {
                            $this->search->saveForStudent($search, $user);
                        }
                        break;

                    case 'login':
                        $data = $request->get('login', []);
                        $user = $this->dispatchFromArray(
                            LoginCommand::class,
                            $data
                        );

                        $createJob = $data['create_job'];

                        //attach search to user.
                        if ($search) {
                            $this->search->saveForStudent($search, $user);
                        }
                        break;

                    default:
                        $user      = $this->auth->user();
                        $createJob = $request->get('create_job', false);
                }

                if (!($user instanceof Student)) {
                    throw new UnauthorizedException();
                }

                $job = null;
                if ($createJob) {
                    //create job
                    try {
                        $job = $this->dispatchFrom(CreateJobCommand::class, $request, [
                            'student'  => $user,
                            'message'  => $request->body,
                            'tutor' => $request->to,
                        ]);
                        $student = $user;
                        $settings = $student->settings;
                        $settings->receive_requests = 1;
                        $settings->save();
                    } catch (DuplicateJobException $e) {
                        // Just do not create a duplicated job
                    }
                } else {
                    $student = $user;
                    $settings = $student->settings;
                    $settings->receive_requests = 0;
                    $settings->save();
                }

                //create relationship
                $relationship = $this->dispatchFromArray(FormRelationshipCommand::class, [
                    'student' => $user,
                    'tutor'   => $request->to,
                    'search'  => $this->search->getById(session('search_id')),
                ]);


                //send message
                $message = $this->dispatchFrom(SendMessageCommand::class, $request, [
                    'relationship' => $relationship,
                ]);

                if ($job) {
                    $job->messageLines()->attach($message->lines->last());
                }

                $redirect = $user instanceof Tutor
                    ? relroute('tutor.messages.show', ['uuid' => $message->uuid])
                    : relroute('student.messages.show', ['uuid' => $message->uuid]);

                return $this->respondWithCreatedItem(
                    $message,
                    new MessageTransformer(),
                    [
                        'meta' => [
                            'redirect' => $redirect,
                        ],
                    ]
                );
            });
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage);
        } catch (ValidationException $e) {
            $errors = $this->prefixErrors($e->getErrors(), $action);

            return $this->respondWithBadRequest($e->getMessage(), $errors);
        } catch (AuthException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (DuplicateResourceException $e) {
            $errors = $this->prefixErrors($e->getErrors(), $action);

            return $this->respondWithConflict('ERR_INVALID', $errors);
        }
    }

    protected
    function prefixErrors(
        $errors,
        $action
    ) {
        $prefix = ['first_name', 'last_name', 'email', 'telephone', 'password', 'remember_me'];

        foreach ($errors as &$error) {
            $field = array_get($error, 'field');

            if (in_array($field, $prefix) && isset($action)) {
                array_set($error, 'field', "{$action}.{$field}");
            }
        }

        return $errors;
    }
}

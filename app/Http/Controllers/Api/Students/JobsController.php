<?php namespace App\Http\Controllers\Api\Students;

use App\Http\Controllers\Api\ApiController;

use App\Auth\Exceptions\AuthException;
use App\Auth\Exceptions\UnauthorizedException;

use App\Commands\Jobs\CreateJobCommand;

use App\Student;
use App\Tutor;
use App\Transformers\JobTransformer;
use App\Validators\Exceptions\ValidationException;

use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Http\Request;

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
     * Create an instance of this
     *
     * @param  Database $database
     * @param  Auth     $auth
     */
    public function __construct(
        Database                    $database,
        Auth                        $auth
    ) {
        $this->database = $database;
        $this->auth     = $auth;
    }

    /**
     * Create tutor request
     */
    public function create(Request $request, $uuid)
    {
        try {
            return $this->database->transaction(function () use ($request, $uuid) {

                //create job
                $job = $this->dispatchFrom(CreateJobCommand::class, $request, [
                    'student'     => null,
                    'studentUuid' => $uuid,
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
        } catch (AuthException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

}

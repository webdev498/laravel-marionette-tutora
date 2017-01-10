<?php namespace App\Http\Controllers\Api\Users;

use App\Presenters\TutorPresenter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Transformers\TutorTransformer;
use App\Http\Controllers\Api\ApiController;
use App\Commands\StudentTutorsCommand;
use App\Validators\Exceptions\ValidationException;
use App\Search\Exceptions\NotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;

class TutorsController extends ApiController
{

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * Create an instance of the handler.
     *
     * @param  UserRepositoryInterface  $users
     */
    public function __construct(
        UserRepositoryInterface  $users
    ) {
        $this->users = $users;
    }

    /**
     * Create new subjects on the user
     *
     * @param  Request $request
     * @param  string  $uuid
     *
     * @return Response
     */
    public function get(Request $request, $uuid)
    {
        try {
            list($tutors, $student) = $this->dispatchFrom(StudentTutorsCommand::class, $request, [
                'uuid' => $uuid,
            ]);

            return $this->transformCollection($tutors, new TutorPresenter([], ['student' => $student]),
                [
                    'include' => [
                        'actions',
                    ]
                ]);
        } catch (NotFoundException $e) {
            return $this->respondWithNotFound($e->getMessage());
        }
    }

}

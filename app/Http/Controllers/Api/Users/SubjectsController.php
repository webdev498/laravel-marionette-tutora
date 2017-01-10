<?php namespace App\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use App\Transformers\SubjectTransformer;
use App\Http\Controllers\Api\ApiController;
use App\Commands\TutorSubjectsUpdateCommand;
use App\Validators\Exceptions\ValidationException;

class SubjectsController extends ApiController
{

    /**
     * Create new subjects on the user
     *
     * @param  Request $request
     * @param  string  $uuid
     * @return Response
     */
    public function create(Request $request, $uuid)
    {
        try {
            $subjects = $this->dispatchFrom(TutorSubjectsUpdateCommand::class, $request, [
                'uuid' => $uuid,
            ]);

            return $this->transformCollection($subjects, new SubjectTransformer());;
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        }
    }

}

<?php namespace App\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use App\Transformers\SubjectTransformer;
use App\Http\Controllers\Api\ApiController;
use App\Commands\QuizSubmitCommand;
use App\Validators\Exceptions\ValidationException;

class QuizController extends ApiController
{

    /**
     * Create new subjects on the user
     *
     * @param  Request $request
     * @param  string  $uuid
     * @return Response
     */
    public function submit(Request $request, $uuid)
    {
        try {
            $results = $this->dispatchFrom(QuizSubmitCommand::class, $request, [
                'uuid' => $uuid,
            ]);

            return $this->respondWithUpdatedItem($results, function ($results) {
                return $results;
            });
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        }
    }

}

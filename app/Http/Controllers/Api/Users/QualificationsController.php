<?php namespace App\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Transformers\QualificationsTransformer;
use App\Commands\TutorQualificationsUpdateCommand;
use App\Validators\Exceptions\ValidationException;

class QualificationsController extends ApiController
{

    public function create(Request $request, $uuid)
    {
        try {
            $tutor = $this->dispatchFrom(
                TutorQualificationsUpdateCommand::class,
                $request,
                [
                    'uuid' => $uuid,
                ]
            );

            return $this->transformItem($tutor, new QualificationsTransformer());
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        }
    }

}

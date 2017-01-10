<?php namespace App\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Transformers\UserTransformer;
use App\Commands\TutorBackgroundChecksUpdateCommand;
use App\Validators\Exceptions\ValidationException;

class BackgroundChecksController extends ApiController
{

    public function create(Request $request, $uuid)
    {
        try {
            $tutor = $this->dispatchFrom(
                TutorBackgroundChecksUpdateCommand::class,
                $request,
                [
                    'uuid' => $uuid,
                ]
            );

            return $this->transformItem($tutor, new UserTransformer(), [
                'include' => [
                    'background_checks'
                ],
            ]);
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        }
    }

}

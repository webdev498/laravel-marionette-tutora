<?php namespace App\Http\Controllers\Api\Users\BackgroundChecks;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Transformers\BackgroundCheckTransformer;
use App\Commands\BackgroundChecks\TutorBackgroundUpdateCommand;
use App\Commands\BackgroundChecks\TutorBackgroundCreateCommand;
use App\Commands\BackgroundChecks\DeleteTutorBackgroundCheckCommand;
use App\Validators\Exceptions\ValidationException;
use App\Auth\Exceptions\AuthException;

class BackgroundCheckController extends ApiController
{

    public function create(Request $request, $uuid, $type)
    {
        try {
            $background = $this->dispatchFrom(
                TutorBackgroundCreateCommand::class,
                $request,
                [
                    'uuid'       => $uuid,
                    'type'       => $type,
                ]
            );

            return $this->transformItem($background, new BackgroundCheckTransformer(), [
                'include' => [
                    'image'
                ],
            ]);
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        }
    }

    public function update(Request $request, $uuid, $type)
    {
        try {
            $background = $this->dispatchFrom(
                TutorBackgroundUpdateCommand::class,
                $request,
                [
                    'uuid'       => $uuid,
                    'type'       => $type,
                ]
            );

            return $this->transformItem($background, new BackgroundCheckTransformer(), [
                'include' => [
                    'image'
                ],
            ]);
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        }
    }

    public function delete(Request $request, $uuid, $type)
    {
        try {
            $this->dispatchFrom(
                DeleteTutorBackgroundCheckCommand::class,
                $request,
                [
                    'uuid'       => $uuid,
                    'type'       => $type,
                ]
            );

            return $this->respondWithArray([
                'success' => true,
                'uuid' => $uuid
            ], 200);
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (AuthException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

}

<?php

namespace App\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use App\Exceptions\AppException;
use App\Auth\Exceptions\AuthException;
use App\Http\Controllers\Api\ApiController;
use App\Commands\UploadIdentityDocumentCommand;
use App\Validators\Exceptions\ValidationException;

class IdentityDocumentController extends ApiController
{

    /**
     * Create an identification file
     *
     * @param  Request $request
     * @param  string $uuid
     * @return string
     */
    public function create(Request $request, $uuid)
    {
        try {
            $id = $this->dispatchFrom(UploadIdentityDocumentCommand::class, $request, [
                'uuid' => $uuid,
            ]);

            return $id;
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (AuthException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (AppException $e) {
            return $this->respondWithBadRequest($e->getMessage());
        }
    }

}

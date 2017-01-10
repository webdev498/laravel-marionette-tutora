<?php namespace App\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Commands\ProfilePictureUploadCommand;
use App\Validators\Exceptions\ValidationException;

class ProfilePictureController extends ApiController
{

    public function create(Request $request, $uuid)
    {
        try {
            $results = $this->dispatchFromArray(ProfilePictureUploadCommand::class, [
                'uuid'    => $uuid,
                'picture' => $request->file('picture'),
            ]);

            return $this->respondWithCreatedItem($results, function ($results) {
                return $results;
            });
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        }
    }

}

<?php namespace App\Http\Controllers\Api\Users;

use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager as Auth;
use App\Commands\UserProfileUpdateCommand;
use App\Http\Controllers\Api\ApiController;
use App\Validators\Exceptions\ValidationException;

class ProfileController extends ApiController
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * Create an instance of this
     *
     * @param  Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }


    /**
     * Update an existing user profile
     *
     * @param  Request $request
     * @return UserProfile
     */
    public function edit(Request $request)
    {
        try {
            $profile = $this->dispatchFrom(UserProfileUpdateCommand::class, $request);
            return $profile;
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        }
    }

}

<?php namespace App\Http\Controllers\Api;

use App\Admin;
use App\Tutor;
use App\UserProfile;
use Illuminate\Http\Request;
use App\Commands\LoginCommand;
use App\Transformers\UserTransformer;
use App\Auth\Exceptions\AuthException;
use Illuminate\Session\Store as Session;
use App\Validators\Exceptions\ValidationException;

class SessionsController extends ApiController
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param  Session $session
     * @return void
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Create a new session
     *
     * @param  Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        try {
            $user = $this->dispatchFrom(LoginCommand::class, $request);

            $redirect = $this->session->pull('url.intended');
            if ( ! $redirect) {
                switch (true) {
                    case $user instanceof Admin:
                        $redirect = route('admin.dashboard.index');
                        break;

                    case $user instanceof Tutor:
                        $redirect = $user->profile && in_array($user->profile->status, [
                                UserProfile::LIVE,
                                UserProfile::OFFLINE
                            ])
                            ? route('tutor.dashboard.index')
                            : route('tutor.profile.show', [
                                'uuid' => $user->uuid,
                            ]);
                        break;

                    default:
                        $redirect = route('student.dashboard.index');
                        break;
                }
            }

            return $this->respondWithItem($user, new UserTransformer(), [
                'meta' => [
                    'redirect' => $redirect,
                ]
            ]);
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (AuthException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

}

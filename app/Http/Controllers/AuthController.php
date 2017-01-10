<?php namespace App\Http\Controllers;

use App\Toast;
use Illuminate\Http\Request;
use App\Commands\ConfirmUserCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Validators\Exceptions\ValidationException;

class AuthController extends Controller
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
     * Log the user out
     *
     * @return Redirect
     */
    public function logout()
    {
        $this->auth->logout();

        return redirect()->route('home');
    }

    /**
     * Confirm a users registration
     *
     * @param  Request $request
     * @return Redirect
     */
    public function confirm(Request $request)
    {
        $success = new Toast(
            'Thanks for confirming your email
                address. Welcome to Tutora!',
            'success'
        );

        $error = new Toast(
            'There was an error confirming your email
                address. Please <a href="'.route('about.index').'"
                >contact support</a>.',
            'error'
        );

        try {
            $confirmed = $this->dispatchFrom(ConfirmUserCommand::class, $request);

            $toast = $confirmed === true ? $success : $error;
        } catch (ValidationException $e) {
            $toast = $error;
        }

        return redirect()
            ->route('home')
            ->with([
                'toast' => $toast,
            ]);
    }

}

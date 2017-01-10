<?php namespace App\Http\Middleware;

use Closure;
use App\Tutor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard as Auth;

class AuthenticateUuid
{

    /**
     * The Guard implementation.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth   = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $this->auth->user();

        if ( ! $user) {
            return $this->unauthorised($request);
        }

        if ( ! $user->isAdmin() && $request->uuid !== $user->uuid) {
            return $this->unauthorised($request);
        }

        return $next($request);
    }

    protected function unauthorised(Request $request)
    {
        if ($request->ajax()) {
            return new JsonResponse([
                'message' => 'ERR_UNAUTHORIZED',
            ], 401);
        } else {
            return redirect()->route('home');
        }
    }

}

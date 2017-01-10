<?php namespace App\Http\Middleware;

use Closure;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard as Auth;

class AuthenticateStudent
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
        $this->auth = $auth;
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
        if ( ! $this->auth->user() instanceof Student) {
            if ($request->ajax()) {
                return new JsonResponse([
                    'message' => 'ERR_UNAUTHORIZED',
                ], 401);
            } else {
                return redirect()->guest('login');
            }
        }

        return $next($request);
    }

}

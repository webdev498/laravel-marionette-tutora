<?php namespace App\Http\Middleware;

use Closure;
use App\Tutor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard as Auth;

class AuthenticateTutor
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
        if ( ! $this->auth->user() instanceof Tutor) {
            if ($request->ajax()) {
                return new JsonResponse([
                    'message' => 'ERR_UNAUTHORIZED',
                ], 401);
            } else {
                $targetUrl = $request->fullUrl();
                session(['url.intended' => $targetUrl]);

                return redirect()->route('auth.login');
            }
        }

        return $next($request);
    }

}

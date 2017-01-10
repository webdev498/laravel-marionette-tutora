<?php

namespace App\Http\Middleware;

use Closure;

class ReissueCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if($request->ajax()) {
            $response->header('X-REISSUED-CSRF-TOKEN', csrf_token());
        }

        return $response;
    }
}

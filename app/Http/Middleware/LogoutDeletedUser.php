<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LogoutDeletedUser
{
    /**
     * Logout and redirect user if the account is deleted or blocked
     *
     * @param $request
     * @param callable $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        // get the current user
        $user = Auth::user();

        // check if the user's deleted_at or blocked_at is not empty
        if ($user && (!empty($user->deleted_at) || !empty($user->blocked_at))) {

            // log the user out
            Auth::logout();
            return redirect('/');
        }

        return $next($request);
    }
}
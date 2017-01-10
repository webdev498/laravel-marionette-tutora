<?php

namespace App\Http\Middleware;

use Closure;
use App\Dialogue\UserDialogue;
use Auth;

class CheckIfSuspended
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
        $user = Auth::user();

        if (
            $user
            && !empty($user->suspended_at)
        ) {
            // show undismissable dialogue
            return UserDialogue::Show("contact_us", ['dialogue' => 'contact_us'], route("tutor.dashboard.index", [], false));
        }

        return $next($request);
    }
}

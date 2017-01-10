<?php namespace App\Http\Middleware;

use App\Dialogue\UserDialogue;
use App\Dialogue\UserDialogueInteraction;
use App\Tutor;
use App\UserProfile;
use Closure;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Http\Request;

class TutorMessagesMiddleware
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
        $user = $this->auth->user();
        $route = $request->route()->getName();

        $id = $request->route()->__get('id');
        $dialogue = $request->route()->__get('dialogue');

        // show the trial lessons booking dialog

        if ($route == 'tutor.messages.show'
            && !$dialogue
            && !UserDialogueInteraction::existsForUser($user, "book_trial_lesson")
            && $user instanceof Tutor 
            && $user->profile->admin_status == UserProfile::OK 
            )
        {

            return UserDialogue::Show("book_trial_lesson", ['id' => $id], route("tutor.messages.show", ['id' => $id], false));
        }

        return $next($request);
    }

}

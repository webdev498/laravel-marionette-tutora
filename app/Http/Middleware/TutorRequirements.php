<?php namespace App\Http\Middleware;

use App\UserRequirement;
use Closure;
use App\Tutor;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard as Auth;

class TutorRequirements
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
        $user = $this->auth->user();

        // If tutor is already accepted, check to see if they have done the quiz.
        if ( $user instanceof Tutor && $user->profile->admin_status == UserProfile::OK) {
            $section = $request->route()->parameter('section');
            if( $section != 'quiz_intro' && $section != 'quiz_prep' && $section != 'quiz_questions') {
                
                $requirements = $user->requirements;

                if ($requirements->onlyPending(UserRequirement::QUIZ_QUESTIONS)) 
                {
                    // Haven't done the quiz - redirect to complete quiz
                    return redirect()->route('tutor.profile.show', [$this->auth->user()->uuid, 'quiz_intro']);
                }

            }
        }

        return $next($request);
    }

}

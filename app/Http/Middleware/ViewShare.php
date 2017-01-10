<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Admin;
use App\Tutor;
use App\Student;
use Illuminate\Http\Request;
use App\Dialogue\UserDialogue;
use Illuminate\Auth\Guard as Auth;
use App\Transformers\UserTransformer;
use Illuminate\Filesystem\Filesystem;
use App\Transformers\TutorTransformer;
use App\Transformers\TransformerTrait;
use App\Transformers\StudentTransformer;
use App\Transformers\SessionVarsTransformer;
use Illuminate\Session\SessionManager as Session;
use Cache;

class ViewShare
{

    use TransformerTrait;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var Session
     */
    protected $session;

    /**
     * Create an instance of this
     *
     * @param  Auth    $auth
     * @param  Session $session
     * @return void
     */
    public function __construct(
        Auth       $auth,
        Session    $session,
        Filesystem $filesystem
    ) {
        $this->auth       = $auth;
        $this->session    = $session;
        $this->filesystem = $filesystem;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->ajax()){
            return $next($request);
        }

        $user = $this->auth->user();

        view()->share([
            'user'     => $user,
            'preload'  => [],
            '_preload' => [
                'csrf_token' => csrf_token(),
                'live'       => app()->environment(['production']),
                'user'       => $this->preloadUser($user),
                'lang'       => [
                    'qualifications'   => trans('qualifications'),
                    'background_check' => trans('background_check'),
                    'exceptions'       => trans('exceptions'),
                    'user_profiles'    => trans('user_profiles'),
                    'validation'       => trans('validation'),
                    'dialogue'         => trans('dialogue'),
                    'toast'            => trans('toast'),
                    'dashboard'        => trans('dashboard'),
                    'jobs'             => trans('jobs'),
                    'relationships'    => trans('relationships'),
                ],
                'services' => [
                    'stripe' => [
                        'publishable' => env('STRIPE_PUBLISHABLE'),
                    ],
                    'pusher' => [
                        'key' => env('PUSHER_KEY'),
                    ]
                ],
                'toasts'       => $this->preloadToasts(),
                'session_vars' => $this->preloadSessionVars(),
                'dialogue_routes' => $this->getDialogueRoutes(),
            ],
        ]);

        return $next($request);
    }

    protected function preloadUser(User $user = null)
    {
        if ($user instanceof Admin) {
            return $this->transformItem($user, new UserTransformer(), [
                'include' => [
                    'private',
                ],
            ]);
        } elseif ($user instanceof Tutor) { 
            return $this->transformItem($user, new TutorTransformer(), [
                'include' => [
                    'private',
                    'students',         //
                    'students.private', // needed for booking a lesson
                    'subjects',         //
                ],
            ]);
        } else if ($user instanceof Student) {
            return $this->transformItem($user, new StudentTransformer(), [
                'include' => [
                    'private',
                    'addresses', // needed for retrying payments
                ],
            ]);
        }
    }

    protected function preloadToasts()
    {
        $toasts = [];

        if (($toast = $this->session->pull('toast')) !== null) {
            $toasts[] = $toast->toArray();
        }

        return $toasts;
    }

    protected function preloadSessionVars()
    {
        return $this->transformItem($this->session, new SessionVarsTransformer(), [
            'include' => [
                'query',
            ],
        ]);
    }

    protected function getDialogueRoutes()
    {
        $dialogues = Cache::rememberForever('dialogue_routes.'.UserDialogue::BASIC, function() {
            return UserDialogue::where("type", UserDialogue::BASIC)->get();
        });
        $items = [];

        foreach($dialogues as $dialogue)
        {
            $item = new \stdClass;

            $item->route_string = $dialogue->route;
            $item->name = $dialogue->name;
            $item->id = $dialogue->id;

            $items[] = $item;
        }

        return $items;
    }

}

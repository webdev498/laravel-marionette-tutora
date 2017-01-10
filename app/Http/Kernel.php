<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
        'App\Http\Middleware\VerifyCsrfToken',
        'App\Http\Middleware\ViewShare',
        'App\Http\Middleware\ReissueCsrfToken',
        'App\Http\Middleware\LogoutDeletedUser',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'         => 'App\Http\Middleware\Authenticate',
        'auth.basic'   => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'auth.tutor'   => 'App\Http\Middleware\AuthenticateTutor',
        'auth.student' => 'App\Http\Middleware\AuthenticateStudent',
        'auth.admin'   => 'App\Http\Middleware\AuthenticateAdmin',
        'auth.uuid'    => 'App\Http\Middleware\AuthenticateUuid',
        'tutor.requirements' => 'App\Http\Middleware\TutorRequirements',
        'tutor.messages'   => 'App\Http\Middleware\TutorMessagesMiddleware',
        'guest'        => 'App\Http\Middleware\Guest',
    ];

}

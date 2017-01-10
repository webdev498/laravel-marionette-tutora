<?php namespace App\Providers;

use Auth;
use App\Auth\UserProvider;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function register()
    {
        Auth::extend('app', function($app) {
            $hasher = app('Illuminate\Contracts\Hashing\Hasher');
            return new UserProvider($hasher, 'App\User');
        });
    }

}

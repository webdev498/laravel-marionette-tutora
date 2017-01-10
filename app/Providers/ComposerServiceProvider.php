<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('_.partials.site-nav.authed.tutor', 'App\Http\ViewComposers\TutorNavigationComposer');
        view()->composer('_.partials.site-nav.authed.student', 'App\Http\ViewComposers\StudentNavigationComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

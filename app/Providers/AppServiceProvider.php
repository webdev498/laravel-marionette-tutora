<?php namespace App\Providers;

use Validator;
use App\Http\ViewComposers\PreloadViewComposer;
use App\Validators\CustomValidationRules;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Bugsnag::setBeforeNotifyFunction("before_bugsnag_notify");

        Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomValidationRules($translator, $data, $rules, $messages);
        });

        view()->composer('*', PreloadViewComposer::class);
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );
    }

}

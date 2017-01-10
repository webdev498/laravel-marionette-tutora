<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Ziggeo;

class ZiggeoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Ziggeo',
            function ($app) {
                return new Ziggeo(
                    '35d1cf5177876d75375151131115a57b',
                    '24b1fdf68e345fafc8ebc59868df2c88',
                    '5bf482c493f381c6732958afc5507dac'
                );
            }
        );
    }
}

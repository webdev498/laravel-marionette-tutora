<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BillingServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Billing\Contracts\BillingInterface',
            'App\Billing\StripeBilling'
        );
    }

}

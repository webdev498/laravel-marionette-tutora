<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Geocode\GoogleMapsGeocoder;
use App\Geocode\Contracts\GeocoderInterface;
use Geocoder\HttpAdapter\CurlHttpAdapter;
use Geocoder\Provider\GoogleMapsProvider;
use Geocoder\Geocoder;

class GeocodeServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            GeocoderInterface::class,
            function ($app) {
                $cache    = $app->make('Illuminate\Cache\Repository');
                $adapter  = new CurlHttpAdapter();
                $provider = new GoogleMapsProvider(
                    $adapter,
                    null,
                    null,
                    true,
                    config('services.google-maps.secret')
                    );
                $geocoder = new Geocoder($provider);
                return new GoogleMapsGeocoder($geocoder, $cache);
            }
        );
    }

}

<?php namespace App\Observers;

use App\Address;
use App\Geocode\Contracts\GeocoderInterface;

class AddressObserver
{

    /**
     * @var GeocoderInterface
     */
    protected $geocoder;

    /**
     * Create an instance of the observer.
     *
     * @param  GeocoderInterface $geocoder
     * @return void
     */
    public function __construct(GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public function saving(Address $address)
    {
        $parts = array_filter([
            $address->line_1,
            $address->line_2,
            $address->line_3,
            $address->postcode
        ]);

        // Do not geocode addresses that are new
        // and already 'coordinated'. This is a fast
        // way to eat through geocoding quota
        if (
            $address->exists === false &&
            $address->latitude !== null &&
            $address->longitude !== null
        ) {
            return;
        }

        if ( ! empty($parts)) {
            $query    = implode(', ', $parts);
            $location = $this->geocoder->geocode($query);

            $address->latitude  = $location->latitude;
            $address->longitude = $location->longitude;
            $address->city      = $location->city;
            $address->county      = $location->county;
        } else {
            $address->latitude  = null;
            $address->longitude = null;
            $address->city      = null;
            $address->county    = null;
        }
    }

}

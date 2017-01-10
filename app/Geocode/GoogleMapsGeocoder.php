<?php namespace App\Geocode;

use Config;
use Geocoder\Geocoder;
use Illuminate\Cache\Repository as Cache;
use App\Geocode\Contracts\GeocoderInterface;
use App\Geocode\Exceptions\NoResultException;
use Geocoder\Exception\NoResultException as VendorNoResultException;

class GoogleMapsGeocoder implements GeocoderInterface
{

    /**
     * @var Geocoder\Geocoder
     */
    protected $geocoder;

    /**
     * @var Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * Create an instance of self
     *
     * @param Cache $cache
     * @return void
     */
    public function __construct(Geocoder $geocoder, Cache $cache)
    {
        $this->geocoder = $geocoder;
        $this->cache    = $cache;
    }

    /**
     * Geocode a given query
     *
     * @param  String $query
     * @return Location
     */
    public function geocode($query)
    {
        if (empty($query)) {
            return null;
        }
        $key = str_slug($query, '_');

        if ($this->cache->has("geocode.{$key}")) {
            return $this
                ->cache
                ->get("geocode.{$key}");
        }

        try {
            $geocoded = $this->geocoder->geocode($query.', UK');
        } catch (VendorNoResultException $e) {
            throw new NoResultException();
        }

        $location = new Location([
            'latitude'  => $geocoded->getLatitude(),
            'longitude' => $geocoded->getLongitude(),
            'number'    => $geocoded->getStreetNumber(),
            'street'    => $geocoded->getStreetName(),

            'city'      => $geocoded->getCity(),
            'county'    => $geocoded->getCounty(),
            'region'    => $geocoded->getRegion(),
            'country'   => $geocoded->getCountry(),
            'postcode'  => $geocoded->getZipcode(),
        ]);

        $this->cache
            ->put(
                "geocode.{$key}",
                $location,
                Config::get('search.cache.expires')
            );
        return $location;
    }

}

<?php namespace App\Search;

use Geocoder\Exception\NoResultException;
use App\Geocode\Contracts\GeocoderInterface;
use App\Search\Exceptions\LocationNotFoundException;

class LocationSearcher extends AbstractSearcher
{

    /**
     * @var Geocoder
     */
    protected $geocoder;

    /**
     * Create an instance of the searcher
     *
     * @param  GeocoderInterface $geocoder
     * @return void
     */
    public function __construct(GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public function search($query)
    {
        try {
            if (empty($query)) {
                return null;
            }
            
            $terms = $this->extractTerms($query);
            $query = implode($terms, ' ');

            return $this->geocoder->geocode($query);
        } catch (NoResultException $e) {
            throw new LocationNotFoundException($e);
        } catch (\App\Geocode\Exceptions\NoResultException $e) {
            throw new LocationNotFoundException($e);
        }
    }

}

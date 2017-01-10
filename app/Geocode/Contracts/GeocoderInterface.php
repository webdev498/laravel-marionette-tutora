<?php namespace App\Geocode\Contracts;

interface GeocoderInterface
{

    /**
     * Geocode a given query
     *
     * @param  String $query
     * @return Location
     */
    public function geocode($query);

}

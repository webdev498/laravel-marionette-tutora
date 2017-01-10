<?php namespace App\Geocode;

class Location
{

    public function __construct($attrs)
    {
        $keys = [
            'latitude', 'longitude', 'number', 'street', 'city', 'county',
            'region', 'country', 'postcode'
        ];

        $attrs = array_only($attrs, $keys);

        foreach ($keys as $key) {
            $this->$key = array_get($attrs, $key, null);
        }
    }

    public function __toString()
    {
        // Find a pretty location name
        $parts = array_filter([
            $this->city ?: $this->county,
            // $this->county
        ]);

        // Or, just pop the first part of the postcode off
        if ( ! $parts) {
            $parts = head(explode(' ', $this->postcode));
        }

        return implode((array) $parts, ', ');
    }
}

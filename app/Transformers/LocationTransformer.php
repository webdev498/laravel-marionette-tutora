<?php namespace App\Transformers;

use App\Location;
use League\Fractal\TransformerAbstract;

class LocationTransformer extends TransformerAbstract
{

    /**
     * Turn this object into a generic array
     *
     * @param Location $location
     *
     * @return array
     */
    public function transform(Location $location)
    {
        return [
            'uuid'     => (string) $location->uuid,
            'postcode' => (string) $location->postcode,
        ];
    }

}

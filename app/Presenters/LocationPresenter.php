<?php namespace App\Presenters;

use App\Location;
use League\Fractal\TransformerAbstract;

class LocationPresenter extends TransformerAbstract
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

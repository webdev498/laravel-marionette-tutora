<?php

namespace App\Presenters;

use App\Address;
use Illuminate\Support\Collection;

class AddressesPresenter extends AbstractPresenter
{
    /**
     * Turn this object into a generic array
     *
     * @param  User $user
     * @return array
     */
    public function transform(Collection $addresses)
    {
        $data = [];

        foreach ($addresses as $address) {
            $data[$address->pivot->name] = [
                'line_1'    => $address->line_1,
                'line_2'    => $address->line_2,
                'line_3'    => $address->line_3,
                'postcode'  => $address->postcode,
                'city'      => $address->city,
                'latitude'  => $address->latitude,
                'longitude' => $address->longitude,
                'string'    => $this->formatAsString($address),
            ];
        }

        return $data;
    }

    /**
     * Format into human friendly string
     *
     * @param  User $user
     * @return array
     */
    protected function formatAsString(Address $address)
    {
        $string = implode(', ', [ucfirst($address->line_1), ucfirst($address->line_2), ucfirst($address->city), $address->postcode]);

        return $string;
    }

}

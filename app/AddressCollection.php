<?php namespace App;

use Illuminate\Database\Eloquent\Collection;

class AddressCollection extends Collection
{

    public function __get($name)
    {
        return $this->first(function ($i, $address) use ($name) {
            return $address->pivot->name === $name;
        });
    }

}
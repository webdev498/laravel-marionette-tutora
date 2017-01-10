<?php namespace App\Observers;

use App\User;
use App\Address;

class UserObserver
{

    public function filling(User $user)
    {
        $attributes = $user->getAttributes();

        if (array_key_exists('addresses', $attributes)) {
            $addresses = $user->pullAttribute('addresses');

            if (array_key_exists(Address::NORMAL, $addresses)) {
                $address = array_pull($addresses, Address::NORMAL);
                $user->addresses->default->fill($address);
            }

            if (array_key_exists(Address::BILLING, $addresses)) {
                $address = array_pull($addresses, Address::BILLING);
                $user->addresses->billing->fill($address);
            }
        }
    }

}

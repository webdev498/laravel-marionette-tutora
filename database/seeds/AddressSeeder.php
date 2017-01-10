<?php

use App\Address;

class AddressSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getUsers() as $user) {
            foreach ([Address::NORMAL, Address::BILLING] as $name) {
                $user->addresses()->attach(
                    factory(Address::class)->create(),
                    compact('name')
                );
            }
        }
    }
}

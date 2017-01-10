<?php

use App\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->delete();

        $locationSearcher = app('App\Search\LocationSearcher');

        $database  = DB::connection('sqlite_seed_sheffield_postcodes');
        $addresses = $database->select('select * from addresses;');

        foreach($addresses as $address) {
            $location = factory(Location::class)->create();

            $postcode = $address->postcode;

            $location->postcode  = $postcode;
            $location->latitude  = $address->lat;
            $location->longitude = $address->lng;

            $location->save();
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Address;
use App\Geocode\Contracts\GeocoderInterface;
use App\Geocode\Exceptions\NoResultException;
use Illuminate\Database\DatabaseManager;


class GeocodeAddressesCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:geocode_addresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Geocode Addresses in Database';
    /**
     * @var SubjectRepositoryInterface
     */
    protected $geocoder;

    /**
     * @param GeocoderInterface $geocoder
     */
    public function __construct(
        GeocoderInterface $geocoder
    ) {
        parent::__construct();

        $this->geocoder = $geocoder;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $addresses = Address::where('city', '=', null)->whereNotNull('latitude')->limit(500)->get();




        // return $addresses;
        foreach ($addresses as $address)
        {
            try {
                $address->save();
            } catch (NoResultException $e) {
                
            }
        }
    }
}

<?php namespace App\Repositories;

use App\Tutor;
use App\Address;
use App\Geocode\Location;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotPersistedException;
use App\Repositories\Contracts\AddressRepositoryInterface;

class EloquentAddressRepository extends AbstractEloquentLocationRepository implements
    AddressRepositoryInterface
{

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Address
     */
    protected $address;

    /**
     * Create an instance of the repository
     *
     * @param  Database $database
     * @param  Address  $address
     * @return void
     */
    public function __construct(
        Database $database,
        Address  $address
    ) {
        $this->database = $database;
        $this->address  = $address;
    }

    /**
     * Save an array of addresses
     *
     * @param  Array $addresses
     * @return Collection
     */
    public function saveMany($addresses)
    {
        return $this->database->transaction(function () use ($addresses) {
            foreach ($addresses as $address) {
                if ( ! $address->save()) {
                    throw new ResourceNotPersistedException();
                }
            }

            return $this->address->newCollection($addresses);
        });
    }

    public function findById($id)
    {
        return $this->address
            ->where('id', '=', $id)
            ->first();
    }

    public function findByTutorAndLocation(Tutor $tutor, Location $location)
    {
        $query = $this->database
            ->table('users')
            ->select('users.id as user_id', 'addresses.id as address_id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->where('user_profiles.status', '=', 'live')
            ->where('users.id', '=', $tutor->id);

        $result = $this->addWhereLocation($query, $location)
            ->first();

        if ( ! $result) {
            return;
        }

        $address = $this->findById($result->address_id);

        if ( ! $address) {
            return;
        }

        $address->distance = $result->distance;

        return $address;
    }

}

<?php namespace App\Handlers\Events;

use App\Tutor;
use App\Address;
use App\Events\UserWasRegistered;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\AddressRepositoryInterface;

class CreateUserAddresses extends EventHandler
{

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var AddressRepositoryInterface
     */
    protected $addresses;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * Create the event handler.
     *
     * @param  Database                   $database
     * @param  AddressRepositoryInterface $addresses
     * @param  UserRepositoryInterface    $users
     * @return void
     */
    public function __construct(
        Database                   $database,
        AddressRepositoryInterface $addresses,
        UserRepositoryInterface    $users
    ) {
        $this->database  = $database;
        $this->addresses = $addresses;
        $this->users     = $users;
    }

    /**
     * Handle the event.
     *
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        return $this->database->transaction(function () use ($event) {
            $user  = $event->user;
            $names = [Address::NORMAL, Address::BILLING];

            // Create an address for each name
            $addresses = $this->addresses->saveMany(array_map(function ($name) {
                return new Address();
            }, $names));

            // Build up sync data, associating each address with a name
            $sync = [];
            foreach ($addresses as $address) {
                $sync[$address->id] = [
                    'name' => array_shift($names),
                ];
            }

            // Save
            $user->addresses()->sync($sync);
        });
    }

}

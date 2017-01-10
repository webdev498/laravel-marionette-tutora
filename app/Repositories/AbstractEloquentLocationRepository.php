<?php namespace App\Repositories;

use App\Address;
use App\Location;
use App\Geocode\Location as GeoCodeLocation;
use Illuminate\Database\DatabaseManager as Database;

class AbstractEloquentLocationRepository extends AbstractEloquentRepository
{

    /**
     * Earths radius, in miles
     */
    const EARTHS_RADIUS = 3959;

    /**
     * Search radius, in miles
     */
    const RADIUS = 11;

    /**
     * @var Database
     */
    protected $database;

    /**
     * Create an instance of the repository
     *
     * @param  Database $database
     * @param  Tutor    $tutor
     * @return void
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    protected function addWhereLocation($query, $location)
    {
        $latDeg  = rad2deg(self::RADIUS / self::EARTHS_RADIUS);
        $latFrom = $location->latitude - $latDeg;
        $latTo   = $location->latitude + $latDeg;

        $latCos  = cos(deg2rad($location->latitude));
        $longDeg = rad2deg(self::RADIUS / self::EARTHS_RADIUS / $latCos);
        $longFrom = $location->longitude - $longDeg;
        $longTo   = $location->longitude + $longDeg;

        $db = $this->database->getDatabaseName();

        if($location instanceof Location) {
            $query = $this->addLocationQuery($db, $query, $location, $latFrom, $latTo, $longFrom, $longTo);
        } else {
            $query = $this->addAddressLocationQuery($db, $query, $location, $latFrom, $latTo, $longFrom, $longTo);
        }

        return $query;
    }

    /**
     * @param $db
     * @param $query
     * @param Location $location
     * @param $latFrom
     * @param $latTo
     * @param $longFrom
     * @param $longTo
     *
     * @return mixed
     */
    protected function addLocationQuery($db, $query, Location $location, $latFrom, $latTo, $longFrom, $longTo)
    {
        $distance = "`{$db}`.DISTANCE(
            {$location->latitude},
            {$location->longitude},
            locations.latitude,
            locations.longitude
        )";

        $locatableClass = get_class($query->getModel());
        $targetTable    = $query->getModel()->getTable();

        return $query
            ->addSelect($this->database->raw("{$distance} AS distance"))
            ->join('locatables', function ($join) use ($locatableClass, $targetTable) {
                $join->on('locatables.locatable_id', '=', $targetTable.'.id')
                    ->where('locatables.locatable_type', '=', $locatableClass);
            })
            ->join('locations', 'locatables.location_id', '=', 'locations.id')
            ->whereBetween('locations.latitude', [$latFrom, $latTo])
            ->whereBetween('locations.longitude', [$longFrom, $longTo])
            ->where($this->database->raw($distance), '<', self::RADIUS);
    }

    /**
     * @param $db
     * @param $query
     * @param $location
     * @param $latFrom
     * @param $latTo
     * @param $longFrom
     * @param $longTo
     *
     * @return mixed
     */
    protected function addAddressLocationQuery($db, $query, $location, $latFrom, $latTo, $longFrom, $longTo)
    {
        $distance = "`{$db}`.DISTANCE(
            {$location->latitude},
            {$location->longitude},
            addresses.latitude,
            addresses.longitude
        )";

        return $query
            ->addSelect($this->database->raw("{$distance} AS distance"))
            ->join('address_user', 'address_user.user_id', '=', 'users.id')
            ->join('addresses', 'addresses.id', '=', 'address_user.address_id')
            ->where('address_user.name', '=', Address::NORMAL)
            ->whereBetween('addresses.latitude', [$latFrom, $latTo])
            ->whereBetween('addresses.longitude', [$longFrom, $longTo])
            ->where($this->database->raw($distance), '<', self::RADIUS);
    }

}

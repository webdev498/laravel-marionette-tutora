<?php namespace App;

use App\User;
use App\Address;
use App\Database\Model;
use App\Geocode\Location as GeocodeLocation;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street',
        'city',
        'country',
        'county',
        'postcode',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * A subject has many jobs
     *
     * @return HasMany
     */
    public function jobs()
    {
        return $this->morphedByMany('App\Job', 'locatable');
    }

    /**
     * @param  GeocodeLocation $geoLocation
     *
     * @return Location
     */
    public static function make(GeocodeLocation $geoLocation)
    {
        $location = new static();

        $data = [
            'uuid'      => self::generateUuid(),
            'street'    => $geoLocation->street,
            'city'      => $geoLocation->city,
            'country'   => $geoLocation->country,
            'county'    => $geoLocation->county,
            'postcode'  => preg_replace('/\s+/', '', $geoLocation->postcode),
            'latitude'  => $geoLocation->latitude,
            'longitude' => $geoLocation->longitude,
        ];

        $location->fill($data);

        $location->uuid = self::generateUuid();

        return $location;
    }

    /**
     * @param  Address $address
     *
     * @return Location
     */
    public static function makeFromAddress(Address $address)
    {
        $location = new static();

        $data = [
            'uuid'      => self::generateUuid(),
            'street'    => $address->line_1,
            'city'      => $address->line_2,
            'county'    => $address->line_3,
            'country'   => '',
            'postcode'  => preg_replace('/\s+/', '', $address->postcode),
            'latitude'  => $address->latitude,
            'longitude' => $address->longitude,
        ];

        $location->fill($data);

        $location->uuid = self::generateUuid();

        return $location;
    }

    /**
     * @param Location $location_1
     * @param Location $location_2
     *
     * @return integer
     */
    public static function distance(Location $location_1, Location $location_2)
    {
        $latitude_1 = $location_1->latitude;
        $latitude_2 = $location_2->latitude;

        $longitude_1 = $location_1->longitude;
        $longitude_2 = $location_2->longitude;

        $distance = 3959 * acos(
                sin(deg2rad($latitude_1))
                * sin(deg2rad($latitude_2))
                + cos(deg2rad($latitude_1))
                * cos(deg2rad($latitude_2))
                * cos(deg2rad($longitude_2) - deg2rad($longitude_1))
            );

        return $distance;
    }
}

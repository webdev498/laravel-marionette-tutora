<?php namespace App;

use App\Database\Model;
use App\Observers\AddressObserver;

class Address extends Model
{

    const NORMAL  = 'default';
    const BILLING = 'billing';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'line_1',
        'line_2',
        'line_3',
        'postcode',
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        parent::observe(app(AddressObserver::class));
    }

    /**
     * Create a new AddressCollection instance.
     *
     * @param  array  $models
     * @return AddressCollection
     */
    public function newCollection(array $models = [])
    {
        return new AddressCollection($models);
    }

    public static function edit(
        Address $address,
        $line1,
        $line2,
        $line3,
        $postcode
    ) {
        $address->line_1   = $line1;
        $address->line_2   = $line2;
        $address->line_3   = $line3;
        $address->postcode = $postcode;
        // raise
        return $address;
    }

    /**
     * Get a short location name for the address. This one should
     * be safe for the public to see as "my location"
     *
     * It will be displayed as either "{line 2}, {line 3}", or the first
     * half of the postcode.
     *
     * @return String
     */
    public function getShortName()
    {
        // Find a pretty location name
        $parts = array_filter([
            $this->line_2,
            $this->line_3
        ]);

        // Or, just pop the first part of the postcode off
        if ( ! $parts) {
            $parts = head(explode(' ', $this->postcode));
        }

        return implode((array) $parts, ', ');
    }

}

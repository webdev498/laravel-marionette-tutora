<?php namespace App\Transformers;

use App\Address;
use League\Fractal\ParamBag;
use Illuminate\Support\Collection;

class AddressesTransformer extends AbstractTransformer
{

    protected $options = [];

    public function __construct(Array $options = [])
    {
        $this->options = array_extend([
            'only' => [
                Address::NORMAL,
                Address::BILLING
            ]
        ], $options);
    }

    public function transform(Collection $addresses)
    {
        guard_against_array_of_invalid_arguments($addresses, Address::class);

        $data = [];

        foreach(array_get($this->options, 'only') as $name) {
            $address = $addresses->first(function ($_, $address) use ($name) {
                return $address->pivot->name === $name;
            });

            if ($address === null) {
                continue;
            }

            $data[$name] = [
                'line_1'    => $address->line_1,
                'line_2'    => $address->line_2,
                'line_3'    => $address->line_3,
                'postcode'  => $address->postcode,
                'latitude'  => $address->latitude,
                'longitude' => $address->longitude,
            ];
        }

        return $data;
    }

}

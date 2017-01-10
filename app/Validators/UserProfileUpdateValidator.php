<?php namespace App\Validators;

class UserProfileUpdateValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules($data)
    {
        $rules = [
            'tagline'       => ['string', 'max:60'],
            'rate'          => ['numeric'],
            'travel_policy' => ['numeric', 'in:0,1,5,10,20'],
            'bio'           => ['string'],
            'short_bio'     => ['string', 'max:255']
        ];

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $rules[$key][] = 'required';
            }
        }

        return $rules;
    }

}

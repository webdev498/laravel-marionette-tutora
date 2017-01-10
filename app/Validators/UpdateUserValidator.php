<?php namespace App\Validators;

use Auth;
use App\Address;
use App\UserProfile;

class UpdateUserValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules($data)
    {
        $data  = array_to_object($data);
        $rules = [];

        if ($data->first_name !== null) {
            $rules = $this->rulesForPersonalDetails($rules, $data);
        }

        if ($data->addresses !== null && $data->addresses->default !== null) {
            $rules = $this->rulesForDefaultAddress($rules, $data);
        }

        if ($data->identity_document !== null) {
            $rules = $this->rulesForIdentification($rules, $data);
        }

        if ($data->bank !== null || $data->card !== null) {
            $rules = $this->rulesForPaymentDetails($rules, $data);
        }

        if ($data->profile !== null) {
            $rules = $this->rulesForProfile($rules, $data);
        }

        return $rules;
    }

    /**
     * Add the rules for updating the users personal details
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    protected function rulesForPersonalDetails(Array $rules, $data)
    {
        return array_extend($rules, [
            'first_name'       => ['required', 'min:1', 'max:255'],
            'last_name'        => ['required', 'min:1', 'max:255'],
            'email'            => ['required', 'email', 'unique:users,email,'.$data['uuid'].',uuid', 'min:1', 'max:255'],
            'telephone'        => ['required', 'min:1', 'max:255'],
            'password'         => ['min:6'],
            'reset_password'   => ['sometimes', 'min:6'],
            'confirm_reset_password' => ['required_with:reset_password', 'same:reset_password']
        ]);
    }

    /**
     * Add the rules for updating the users default address
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    protected function rulesForDefaultAddress(Array $rules, $data)
    {
        $year = date('Y');

        return array_extend($rules, [
            'addresses.default.line_1'   => ['required', 'max:255'],
            'addresses.default.line_2'   => ['required', 'max:255'],
            'addresses.default.line_3'   => ['max:255'],
            'addresses.default.postcode' => ['required', 'max:255'],
        ]);
    }

    /**
     * Add the rules for updating identificaiton details
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    protected function rulesForIdentification(Array $rules, $data)
    {
        $year = date('Y');

        return array_extend($rules, [
            'legal_first_name'       => ['required', 'min:1', 'max:255'],
            'legal_last_name'        => ['required', 'min:1', 'max:255'],
            'dob.day'                => ['required', 'numeric', 'min:1', 'max:31'],
            'dob.month'              => ['required', 'numeric', 'min:1', 'max:12'],
            'dob.year'               => ['required', 'numeric', 'min:'.($year - 100), 'max:'.$year],
            'identity_document.uuid' => ['required', 'string', 'size:36'],
        ]);
    }

    /**
     * Add the rules for updating payment details
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    protected function rulesForPaymentDetails(Array $rules, $data)
    {
        $key  = $data->card !== null ? 'card' : 'bank';
        $user = Auth::user();

        return array_extend($rules, [
            $key => array_merge($user->last_four ? [] : ['required'], ['string']),
        ], $this->rulesForAddress('billing', $rules, $data));
    }

    /**
     * Add the rules for updating a users profile
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    protected function rulesForProfile(Array $rules, $data)
    {
        if ($data->profile->tagline !== null) {
            $rules = $this->rulesForProfileTagline($rules, $data);
        }

        if ($data->profile->summary !== null) {
            $rules = $this->rulesForProfileSummary($rules, $data);
        }

        if ($data->profile->rate !== null) {
            $rules = $this->rulesForProfileRate($rules, $data);
        }

        if ($data->profile->travel_radius !== null) {
            $rules = $this->rulesForTravelPolicy($rules, $data);
        }

        if ($data->profile->bio !== null) {
            $rules = $this->rulesForProfileBio($rules, $data);
        }

        if ($data->profile->status) {
            $rules = $this->rulesForProfileStatus($rules, $data);
        }

        return $rules;
    }

    /**
     * Add the rules for updating a users profile tagline
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    protected function rulesForProfileTagline(Array $rules, $data)
    {
        return array_extend($rules, [
            'profile.tagline' => ['required', 'string', 'max:60'],
        ]);
    }
    /**
     * Add the rules for updating a users summary
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    protected function rulesForProfileSummary(Array $rules, $data)
    {
        return array_extend($rules, [
            'profile.summary' => ['string', 'max:60'],
        ]);
    }
    /**
     * Add the rules for updating a users profile rate
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    protected function rulesForProfileRate(Array $rules, $data)
    {
        return array_extend($rules, [
            'profile.rate' => ['required', 'numeric', 'min:15'],
        ]);
    }

    /**
     * Add the rules for updating a users travel policy
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    protected function rulesForTravelPolicy(Array $rules, $data)
    {
        return array_extend($rules, [
            'profile.travel_radius' => ['required', 'numeric', 'in:0,1,2,5,10,20'],
        ], $this->rulesForAddress('default', $rules, $data));
    }

    /**
     * Add the rules for updating a users profile bio
     *
     * @param   Array       $rules
     * @param   ArrayObject $data
     * @return  Array
     */
    protected function rulesForProfileBio(Array $rules, $data)
    {
        return array_extend($rules, [
            'profile.bio'       => ['required', 'string', 'min:500', 'noContact'],
            'profile.short_bio' => ['string', 'max:255', 'noContact'],
        ]);
    }

    /**
     * Add the rules for updating a users profile status
     *
     * @param   Array       $rules
     * @param   ArrayObject $data
     * @return  Array
     */
    protected function rulesForProfileStatus(Array $rules, $data)
    {
        return array_extend($rules, [
            'profile.status' => ['required', 'in:'.implode(',', [
                UserProfile::SNEW,
                UserProfile::SUBMITTABLE,
                UserProfile::PENDING,
                UserProfile::LIVE,
                UserProfile::OFFLINE,
                UserProfile::EXPIRED
            ])]
        ]);
    }

    /**
     * Add the rules for updating an address
     *
     * @param  String      $name
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return void
     */
    protected function rulesForAddress($name, Array $rules, $data)
    {
        return array_extend($rules, [
            "addresses.{$name}.line_1"   => ['required', 'max:255'],
            "addresses.{$name}.line_2"   => ['required', 'max:255'],
            "addresses.{$name}.line_3"   => ['max:255'],
            "addresses.{$name}.postcode" => ['required', 'max:255'],
        ]);
    }
}

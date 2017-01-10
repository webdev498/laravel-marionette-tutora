<?php namespace App\Validators\BackgroundChecks;

use Auth;
use App\Address;
use App\UserProfile;
use App\Validators\Validator;

interface TutorBackgroundValidatorInterface
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules($data);

    /**
     * Add the rules for updating DBS
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    function rulesForDbs(Array $rules, $data);

    /**
     * Add the rules for updating DBS Update
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    function rulesForDbsUpdate(Array $rules, $data);
}

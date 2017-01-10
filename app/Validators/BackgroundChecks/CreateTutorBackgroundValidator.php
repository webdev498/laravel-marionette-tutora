<?php namespace App\Validators\BackgroundChecks;

use Auth;
use App\Address;
use App\UserProfile;
use App\Validators\Validator;
use App\Support\ArrayObject;

class CreateTutorBackgroundValidator extends Validator implements TutorBackgroundValidatorInterface
{

    /**
     * Get the validation rules
     *
     * @param  array $data
     *
     * @return array
     */
    public function rules($data)
    {
        $data  = array_to_object($data);
        $rules = [];

        if ($data->type === 'dbs') {
            $rules = $this->rulesForDbs($rules, $data);
        }

        if ($data->type === 'dbs_update') {
            $rules = $this->rulesForDbsUpdate($rules, $data);
        }

        return $rules;
    }

    /**
     * Add the rules for updating DBS
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    function rulesForDbs(Array $rules, $data)
    {
        return array_extend($rules, [
            'image_upload' => ['required'],
        ]);
    }

    /**
     * Add the rules for updating DBS Update
     *
     * @param  Array       $rules
     * @param  ArrayObject $data
     * @return Array
     */
    function rulesForDbsUpdate(Array $rules, $data)
    {
        return array_extend($rules, [
            'certificate_number' => ['required'],
            'last_name'          => ['required', 'min:1', 'max:255'],
            'dob'                => ['required'],
        ]);
    }
}

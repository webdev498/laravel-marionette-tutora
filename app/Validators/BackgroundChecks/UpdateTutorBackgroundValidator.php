<?php namespace App\Validators\BackgroundChecks;

use Auth;
use App\Address;
use App\UserProfile;
use App\UserBackgroundCheck;
use App\Validators\Validator;
use App\Support\ArrayObject;

class UpdateTutorBackgroundValidator extends Validator implements TutorBackgroundValidatorInterface
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
        $rules = [
            'admin_status' => ['required'],
        ];

        $isRejected = (int)$data['admin_status'] === UserBackgroundCheck::ADMIN_STATUS_REJECTED;

        if(!$isRejected) {
            $rules['issued_at'] = ['required'];
        } else {
            $rules['rejected_for'] = ['required'];
        }

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
        if((int)$data['rejected_for'] === UserBackgroundCheck::DBS_REJECT_REASON_CUSTOM) {
            $rules['reject_comment'] = ['required'];
        }

        return $rules;
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
        $rules = array_extend($rules, [
            'certificate_number' => ['required'],
            'last_name'          => ['required', 'min:1', 'max:255'],
            'dob'                => ['required'],
        ]);

        return $rules;
    }
}

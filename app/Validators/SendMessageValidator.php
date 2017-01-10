<?php

namespace App\Validators;

class SendMessageValidator extends Validator
{
    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules($data)
    {
        $data  = array_to_object($data);
        $rules = [
            'body'         => ['required', 'min:1', 'messageNoContact'],
            'from_system'  => ['required', 'boolean'],
            'silent'       => ['required', 'boolean'],
        ];

        if ($data->relationship) {
            $rules['relationship'] = ['required'];
        } else {
            $rules['uuid'] = ['required', 'string'];
        }

        if ( isset($data->intent) && ! (bool)$data->intent) {
            $rules['reason'] = ['required'];
        }

        return $rules;
    }

}

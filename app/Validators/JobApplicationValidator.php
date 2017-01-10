<?php

namespace App\Validators;

class JobApplicationValidator extends Validator
{
    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules($data)
    {
        $rules = [
            'body' => ['required', 'min:1', 'noContact'],
        ];

        return $rules;
    }

}

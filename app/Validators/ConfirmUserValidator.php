<?php namespace App\Validators;

class ConfirmUserValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'uuid'  => ['required', 'alpha_num'],
            'token' => ['required', 'alpha_num'],
        ];
    }

}

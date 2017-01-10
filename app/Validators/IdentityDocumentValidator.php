<?php

namespace App\Validators;

class IdentityDocumentValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => ['required', 'mimes:jpeg,bmp,png'],
        ];
    }

}

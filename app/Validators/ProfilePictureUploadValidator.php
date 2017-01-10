<?php namespace App\Validators;

class ProfilePictureUploadValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'picture' => ['required', 'image'],
        ];
    }


}

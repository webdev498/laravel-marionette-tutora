<?php namespace App\Validators;

class ImageUploadValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => ['required', 'image', 'max:5000'],
        ];
    }


}

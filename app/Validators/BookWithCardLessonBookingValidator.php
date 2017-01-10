<?php namespace App\Validators;

class BookWithCardLessonBookingValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'    => ['required'],
            'token' => ['required'],
            'user'  => ['required'],
        ];
    }


}

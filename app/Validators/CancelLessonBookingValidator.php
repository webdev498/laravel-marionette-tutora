<?php namespace App\Validators;

class CancelLessonBookingValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'uuid'   => ['required'],
            'future' => ['required', 'boolean'],
        ];
    }


}

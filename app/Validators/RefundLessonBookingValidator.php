<?php namespace App\Validators;

class RefundLessonBookingValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'uuid'   => ['required']
        ];
    }


}

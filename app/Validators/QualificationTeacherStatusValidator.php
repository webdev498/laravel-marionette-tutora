<?php namespace App\Validators;

class QualificationTeacherStatusValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'level' => ['required', 'string'],
        ];
    }


}

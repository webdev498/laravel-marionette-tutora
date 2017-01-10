<?php namespace App\Validators;

class JobValidator extends Validator
{
    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules(&$data)
    {
        $rules = [
            'subject_name'      => ['required'],
            'message'           => ['required'],
            'location_postcode' => ['required', 'regex:/^(GIR ?0AA|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]([0-9ABEHMNPRV-Y])?)|[0-9][A-HJKPS-UW]) ?[0-9][ABD-HJLNP-UW-Z]{2})$/'],
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'subject_name.subject_with_name_exists' => trans('validation.custom.subjects.name.exists')
        ];
    }
}

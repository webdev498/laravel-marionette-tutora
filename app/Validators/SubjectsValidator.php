<?php namespace App\Validators;

class SubjectsValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @param  Array $data
     * @return Array
     */
    public function rules(Array $data)
    {
        $rules = [
            'subjects' => ['required', 'array', 'min:1']
        ];

        if (array_key_exists('subjects', $data)) {
            $keys = array_keys($data['subjects']);
            foreach ($keys as $key) {
                $rules['subjects.'.$key.'.id'] = ['required', 'numeric'];
            }
        }

        return $rules;
    }

}

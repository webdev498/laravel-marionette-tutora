<?php namespace App\Validators;

class QualificationsValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @param  Array $data
     * @return Array
     */
    public function rules(Array $data)
    {
        $rules = [];

        // Do we have any values, anywhere?
        $has = false;

        // Universities
        if (array_key_exists('universities', $data)) {
            foreach ($data['universities'] as $key => $values) {
                if ( ! empty(array_diff($values, ['university' => '', 'subject' => '', 'level' => '']))) {
                    $has = true;
                    $rules['universities.'.$key.'.university']     = ['required', 'string'];
                    $rules['universities.'.$key.'.subject']        = ['required', 'string'];
                    $rules['universities.'.$key.'.level']          = ['required', 'string'];
                    $rules['universities.'.$key.'.still_studying'] = ['required', 'boolean'];
                }
            }
        }

        // Alevels
        if (array_key_exists('alevels', $data)) {
            foreach ($data['alevels'] as $key => $values) {
                if ( ! empty(array_diff($values, ['college' => '', 'subject' => '', 'grade' => '']))) {
                    $has = true;
                    $rules['alevels.'.$key.'.college']        = ['required', 'string'];
                    $rules['alevels.'.$key.'.subject']        = ['required', 'string'];
                    $rules['alevels.'.$key.'.grade']          = ['required', 'string'];
                    $rules['alevels.'.$key.'.still_studying'] = ['required', 'boolean'];
                }
            }
        }

        // Others
        if (array_key_exists('others', $data)) {
            foreach ($data['others'] as $key => $values) {
                if ( ! empty(array_diff($values, ['location' => '', 'subject' => '', 'grade' => '']))) {
                    $has = true;
                    $rules['others.'.$key.'.location']       = ['required', 'string'];
                    $rules['others.'.$key.'.subject']        = ['required', 'string'];
                    $rules['others.'.$key.'.grade']          = ['string'];
                    $rules['others.'.$key.'.still_studying'] = ['required', 'boolean'];
                }
            }
        }

        if ($has === false) {
            unset($data['qualifications']);
            $rules['qualifications'] = ['required'];
        }

        return $rules;
    }

}

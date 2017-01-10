<?php namespace App\Validators;

class UpdateUserReviewValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules(&$data)
    {
        return [
            'rating' => ['required', 'numeric', 'between:1,5'],
            'body' => 'required'
        ];
    }

}

<?php namespace App\Validators;

class CreateUserReviewValidator extends Validator
{

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules(&$data)
    {
        return [
            'uuid'   => ['required'],
            'rating' => ['required', 'numeric', 'between:1,5'],
        ];
    }

}

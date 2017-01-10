<?php namespace App\Validators;

use App\Validators\Exceptions\ValidationException;
use Illuminate\Validation\Factory;

class LoginValidator
{
    protected $validator;

    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'       => 'required|email|max:255',
            'password'    => 'required|min:6',
            'remember_me' => 'boolean',
        ];
    }

    public function validate(array $data)
    {
        $validator = $this->validator->make($data, $this->rules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

}

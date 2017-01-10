<?php namespace App\Validators;

use App\Validators\Exceptions\ValidationException;
use Illuminate\Validation\Factory;
use App\Role;

class RegisterUserValidator
{
    protected $validator;

    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Get the validation rules
     *
     * @param $data
     *
     * @return array
     */
    public function rules($data)
    {
        $roles = [];

        $roles = array_merge($roles, [
            'account'      => 'required|in:'.Role::STUDENT.','.Role::TUTOR,
            'first_name'   => 'required|max:255',
            'last_name'    => 'required|max:255',
            'email'        => 'required|email|max:255',
            'telephone'    => 'required|max:255',
            'password'     => 'required|min:6',
        ]);

        return $roles;
    }

    public function validate(array $data)
    {
        $validator = $this->validator->make($data, $this->rules($data));

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

}

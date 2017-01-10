<?php namespace App\Validators;

use Illuminate\Validation\Factory;
use App\Validators\Exceptions\ValidationException;

abstract class Validator
{
    /**
     * @var Factory
     */
    protected $validator;

    /**
     * Create the validator.
     *
     * @param  Factory $validator
     * @return void
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate the given data against $this->rules().
     *
     * @throws ValidationException
     *
     * @param  Array $data
     * @return boolean
     */
    public function validate(array $data)
    {
        $rules     = $this->rules($data);
        $messages  = method_exists($this, 'messages') ? $this->messages() : [];
        $validator = $this->validator->make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

}

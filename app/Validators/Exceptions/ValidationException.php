<?php namespace App\Validators\Exceptions;

use Exception;
use Illuminate\Validation\Validator;

class ValidationException extends Exception
{
    const CODE_UNKNOWN  = 'ERR_UNKNOWN';
    const CODE_REQUIRED = 'ERR_REQUIRED';
    const CODE_EMAIL    = 'ERR_EMAIL';
    const CODE_LENGTH   = 'ERR_LENGTH';
    const CODE_PHONE    = 'ERR_PHONE';
    const CODE_CONTACT  = 'ERR_CONTACT';

    protected $rulesToCodesMap = [
        'Required' => self::CODE_REQUIRED,
        'Email'    => self::CODE_EMAIL,
        'Min'      => self::CODE_LENGTH,
        'Max'      => self::CODE_LENGTH,
        'NoPhone'    => self::CODE_PHONE,
        'NoEmail'    => self::CODE_EMAIL,
        'NoContact'  => self::CODE_CONTACT,
    ];

    protected $message = 'ERR_INVALID';

    protected $errorBag;

    protected $errors = [];

    public function __construct(Validator $validator)
    {
        $this->errorBag = $validator->errors();
        $this->errors   = $this->parseErrors($validator);
    }

    protected function parseErrors(Validator $validator)
    {
        $failed = $validator->failed();
        $bag    = $validator->errors();
        $errors = [];

        foreach ($failed as $field => $rules) {
            $rules = array_keys($rules);
            $rule  = head($rules);

            $errors[] = [
                'field'  => $field,
                'code'   => $this->getErrorCodeForRule($rule),
                'detail' => $bag->first($field),
            ];
        }

        return $errors;
    }

    protected function getErrorCodeForRule($rule)
    {
        if (isset($this->rulesToCodesMap[$rule])) {
            return $this->rulesToCodesMap[$rule];
        }

        return self::CODE_UNKNOWN;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getErrorBag()
    {
        return $this->errorBag;
    }
}

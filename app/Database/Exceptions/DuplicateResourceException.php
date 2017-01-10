<?php namespace App\Database\Exceptions;

use Exception;

class DuplicateResourceException extends Exception
{
    protected $errors = [];

    public function __construct(array $errors = [])
    {
        $this->errors = array_map(function ($error) {
            $error['code'] = 'ERR_CONFLICT';
            return $error;
        }, $errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}

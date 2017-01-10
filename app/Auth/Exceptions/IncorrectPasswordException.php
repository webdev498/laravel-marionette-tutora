<?php namespace App\Auth\Exceptions;

class IncorrectPasswordException extends AuthException
{

    protected $message = 'ERR_INCORRECT_PASSWORD';

}

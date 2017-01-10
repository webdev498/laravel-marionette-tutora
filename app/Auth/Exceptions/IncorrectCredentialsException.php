<?php namespace App\Auth\Exceptions;

class IncorrectCredentialsException extends AuthException
{

    protected $message = 'ERR_INCORRECT_CREDENTIALS';

}

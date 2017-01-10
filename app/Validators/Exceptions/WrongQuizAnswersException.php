<?php namespace App\Validators\Exceptions;

use Exception;
use Illuminate\Validation\Validator;

class WrongQuizAnswersException extends ValidationException
{
    protected $message = 'ERR_INVALID_QUIZ_ANSWER';
}

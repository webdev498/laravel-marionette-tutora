<?php namespace App\Exceptions\Jobs;

use App\Exceptions\AppException;

class ClosedJobException extends AppException
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        if(!$message) {
            $this->message = trans('exceptions.jobs.closed');
        }
    }
}
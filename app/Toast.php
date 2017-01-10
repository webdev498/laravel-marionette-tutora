<?php namespace App;

use App\Support\ArrayObject;

class Toast extends ArrayObject
{
    const INFO    = 'info';
    const SUCCESS = 'success';
    const ERROR   = 'error';
    const WARNING = 'warning';

    public function __construct($message, $severity, $duration = 5000)
    {
        $this->message  = $message;
        $this->severity = $severity;
        $this->duration = $duration;
    }
}

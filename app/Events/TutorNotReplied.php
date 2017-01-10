<?php

namespace App\Events;

use App\MessageLine;
use App\Tutor;

class TutorNotReplied extends Event
{
    /**
     * Create a new event instance.
     *
     * @param  Tutor $Tutor
     * @return void
     */
    public function __construct(Tutor $tutor, MessageLine $line)
    {
        $this->tutor = $tutor;
        $this->line  = $line;
    }
}
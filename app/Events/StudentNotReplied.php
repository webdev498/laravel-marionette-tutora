<?php

namespace App\Events;

use App\MessageLine;
use App\Student;

class StudentNotReplied extends Event
{
    /**
     * Create a new event instance.
     *
     * @param  Student $student
     * @param  MessageLine $line
     * @return void
     */
    public function __construct(Student $student, MessageLine $line)
    {
        $this->student = $student;
        $this->line    = $line;
    }
}
<?php namespace App\Commands\Jobs;

use App\MessageLine;
use App\Student;
use App\Commands\Command;

class CreateJobCommand extends Command
{

    /**
     * Create a new command instance
     *
     * @param string        $message
     * @param string        $subject_name
     * @param string        $location_postcode
     * @param string        $studentUuid
     * @param string        $forTutor
     * @param Student       $student
     * @param MessageLine   $messageLine
     */
    public function __construct(
        $message,
        $subject_name,
        $location_postcode,
        $studentUuid = null,
        $tutor = null,
        Student $student = null,
        MessageLine $messageLine = null,
        $by_request = null
    ) {
        $this->message           = $message;
        $this->subject_name      = $subject_name;
        $this->location_postcode = strtoupper(preg_replace('/\s+/', '', $location_postcode));
        $this->studentUuid       = $studentUuid;
        $this->student           = $student;
        $this->messageLine       = $messageLine;
        $this->tutor             = $tutor;
        $this->by_request        = $by_request;
    }
}

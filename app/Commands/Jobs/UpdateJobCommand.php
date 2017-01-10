<?php namespace App\Commands\Jobs;

use App\MessageLine;
use App\Commands\Command;

class UpdateJobCommand extends Command
{

    /**
     * Create a new command instance
     *
     * @param string        $uuid
     * @param string        $message
     * @param string        $subject_name
     * @param string        $location_postcode
     * @param string        $status
     * @param string        $closed_for
     * @param MessageLine   $messageLine
     */
    public function __construct(
        $uuid,
        $message,
        $subject_name,
        $location_postcode,
        $status,
        $closed_for = null,
        MessageLine $messageLine = null,
        $by_request
    ) {
        $this->uuid              = $uuid;
        $this->message           = $message;
        $this->subject_name      = $subject_name;
        $this->location_postcode = preg_replace('/\s+/', '', $location_postcode);
        $this->status            = (int) $status;
        $this->closed_for        = $closed_for;
        $this->messageLine       = $messageLine;
        $this->by_request        = $by_request;
    }

}

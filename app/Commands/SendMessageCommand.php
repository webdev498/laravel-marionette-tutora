<?php namespace App\Commands;

use App\Relationship;
use App\Commands\Command;

class SendMessageCommand extends Command
{

    /**
     * @param $body
     * @param Relationship|null $relationship
     * @param null              $uuid
     * @param bool|false        $from_system
     * @param bool|false        $silent
     * @param null              $intent
     * @param null              $reason
     * @param string            $subject_name
     * @param string            $location_postcode
     */
    public function __construct(
        $body,
        Relationship $relationship = null,
        $uuid                      = null,
        $from_system               = false,
        $silent                    = false,
        $intent                    = null,
        $reason                    = null,
        $subject_name              = null,
        $location_postcode         = null
    ) {
        $this->body              = $body;
        $this->relationship      = $relationship;
        $this->uuid              = $uuid;
        $this->from_system       = $from_system;
        $this->silent            = $silent;
        $this->intent            = $intent;
        $this->reason            = $reason;
        $this->subject_name      = $subject_name;
        $this->location_postcode = strtoupper(preg_replace('/\s+/', '', $location_postcode));
    }

}

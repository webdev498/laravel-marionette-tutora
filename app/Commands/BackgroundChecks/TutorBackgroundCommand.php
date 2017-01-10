<?php namespace App\Commands\BackgroundChecks;

use App\Commands\Command;

class TutorBackgroundCommand extends Command
{

    /**
     * @param $uuid
     * @param $type
     * @param null $issued_at
     * @param null $admin_status
     * @param null $image_upload
     * @param null $certificate_number
     * @param null $last_name
     * @param null $dob
     * @param null $rejected_for
     * @param null $reject_comment
     */
    public function __construct(
        $uuid,
        $type,
        $issued_at = null,
        $admin_status = null,
        $image_upload = null,
        $certificate_number = null,
        $last_name = null,
        $dob = null,
        $rejected_for = null,
        $reject_comment = null
    ) {
        $this->uuid               = $uuid;
        $this->type               = $type;
        $this->issued_at          = $issued_at;
        $this->admin_status       = $admin_status;
        $this->image_upload       = $image_upload;
        $this->certificate_number = $certificate_number;
        $this->last_name          = $last_name;
        $this->dob                = $dob;
        $this->rejected_for       = $rejected_for;
        $this->reject_comment     = $reject_comment;
    }

}

<?php namespace App\Commands;

class TutorQualificationsUpdateCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $uuid,
        Array $universities,
        Array $alevels,
        Array $others
    ) {
        $this->uuid         = $uuid;
        $this->universities = $universities;
        $this->alevels      = $alevels;
        $this->others       = $others;
    }


}

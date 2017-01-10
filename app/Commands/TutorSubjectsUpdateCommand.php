<?php namespace App\Commands;

class TutorSubjectsUpdateCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $uuid,
        $subjects
    ) {
        $this->uuid     = $uuid;
        $this->subjects = $subjects;
    }

}

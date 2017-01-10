<?php namespace App\Commands;

class StudentTutorsCommand extends Command
{

    /**
     * @param $uuid
     */
    public function __construct(
        $uuid
    ) {
        $this->uuid     = $uuid;
    }

}

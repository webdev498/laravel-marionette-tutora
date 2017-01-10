<?php namespace App\Commands;

class ProfilePictureUploadCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($uuid, $picture)
    {
        $this->uuid    = $uuid;
        $this->picture = $picture;
    }

}

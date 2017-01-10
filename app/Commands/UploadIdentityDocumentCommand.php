<?php

namespace App\Commands;

class UploadIdentityDocumentCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($uuid, $file)
    {
        $this->uuid = $uuid;
        $this->file = $file;
    }

}

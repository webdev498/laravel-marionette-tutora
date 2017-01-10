<?php namespace App\Commands\Files;

use App\Commands\Command;

class ImageUploadCommand extends Command
{

    /**
     * Create a new command instance.
     */
    public function __construct($image)
    {
        $this->image = $image;
    }

}

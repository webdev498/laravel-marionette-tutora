<?php namespace App\Commands;

use App\Commands\Command;

class QTSUpdateCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @param  String $level
     * @return void
     */
    public function __construct($uuid, $level)
    {
        $this->uuid  = $uuid;
        $this->level = $level;
    }

}

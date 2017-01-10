<?php namespace App\Commands\Messages;

use App\User;
use App\Commands\Command;

class FlagMessageLineCommand extends Command
{

    /**
     * Create a new command instance
     *
     * @param string $uuid
     */
    public function __construct(
        $uuid
    ) {
        $this->uuid = $uuid;
    }

}

<?php namespace App\Commands;

use App\Commands\Command;

class ConfirmUserCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @param  String $uuid  The users uuid
     * @param  String $token The users confirmation token
     * @return void
     */
    public function __construct(
        $uuid  = null,
        $token = null
    ) {
        $this->uuid  = $uuid;
        $this->token = $token;
    }

}

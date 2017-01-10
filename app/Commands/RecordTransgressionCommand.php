<?php namespace App\Commands;

use App\Commands\Command;

class RecordTransgressionCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @param  String $uuid  The users uuid
     * @param  String $token The users confirmation token
     * @return void
     */
    public function __construct(
        $uuid,
        $body
    ) {
        $this->uuid  = $uuid;
        $this->body = $body;
    }

}

<?php namespace App\Commands;

use App\Commands\Command;

class RequestPasswordResetCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @param  String $email
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

}

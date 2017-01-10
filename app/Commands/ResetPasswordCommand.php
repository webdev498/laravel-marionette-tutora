<?php namespace App\Commands;

use App\Commands\Command;

class ResetPasswordCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @param  String $token
     * @param  String $email
     * @param  String $password
     * @return void
     */
    public function __construct($token, $email, $password)
    {
        $this->token    = $token;
        $this->email    = $email;
        $this->password = $password;
    }

}

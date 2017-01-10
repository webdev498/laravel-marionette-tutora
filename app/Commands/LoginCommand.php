<?php namespace App\Commands;

use App\Commands\Command;

class LoginCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $email,
        $password,
        $remember_me
    ) {
        $this->email       = $email;
        $this->password    = $password;
        $this->remember_me = $remember_me;
    }

}

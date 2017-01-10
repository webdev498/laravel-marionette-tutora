<?php namespace App\Commands;

use App\Commands\Command;

class RegisterUserCommand extends Command
{

    /**
     * Create a new command instance.
     */
    public function __construct(
        $account,
        $first_name,
        $last_name,
        $email,
        $telephone,
        $password
    ) {
        $this->account    = $account;
        $this->first_name = $first_name;
        $this->last_name  = $last_name;
        $this->email      = $email;
        $this->telephone  = $telephone;
        $this->password   = $password;
    }

}

<?php namespace App\Commands;

use App\Tutor;
use App\User;
use App\Commands\Command;

class FindUserUnreadMessagesCountCommand extends Command
{

    public function __construct(
        User $user
    ) {
        $this->user    = $user;
    }

}

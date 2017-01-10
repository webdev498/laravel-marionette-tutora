<?php namespace App\Commands\Subscriptions;

use App\Commands\Command;

class UnsubscribeUserCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $token,
        $list
    ) {
        $this->token   = $token;
        $this->list    = $list;

    }

}

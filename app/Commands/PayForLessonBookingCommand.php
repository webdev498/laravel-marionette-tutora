<?php namespace App\Commands;

use App\User;
use App\Commands\Command;

class PayForLessonBookingCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $id,
        $token,
        User $user
    ) {
        $this->id    = $id;
        $this->token = $token;
        $this->user  = $user;
    }

}

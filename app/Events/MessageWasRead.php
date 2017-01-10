<?php

namespace App\Events;

use App\Message;
use App\User;

class MessageWasRead extends Event
{
    /**
     * Create a new event instance.
     *
     * @param  Relationship $relationship
     * @return void
     */
    public function __construct(Message $message, User $user)
    {
        $this->message = $message;
        $this->user = $user;
    }
}

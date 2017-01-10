<?php

namespace App\Handlers\Events;

use App\Events\MessageWasRead;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkMessageAsRead
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageWasRead  $event
     * @return void
     */
    public function handle(MessageWasRead $event)
    {
        $message = $event->message;
        $user = $event->user;

        $messageStatus = $message->status($user);

        $messageStatus->markAsRead();
    }
}

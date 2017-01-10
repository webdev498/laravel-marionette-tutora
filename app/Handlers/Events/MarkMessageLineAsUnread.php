<?php

namespace App\Handlers\Events;

use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\MessageLineRepositoryInterface;
use App\Events\MessageLineWasWritten;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkMessageLineAsUnread
{
    private $messages;
    private $lines;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(MessageRepositoryInterface $messages, MessageLineRepositoryInterface $lines)
    {
        $this->messages = $messages;
        $this->lines = $lines;
    }

    /**
     * Handle the event.
     *
     * @param  MessageLineWasWritten  $event
     * @return void
     */
    public function handle(MessageLineWasWritten $event)
    {
        $line = $event->line;
        $user = $line->user;
        $message = $line->message;
        $statuses = $message->statuses;

        //Mark as unread by other message participant
        foreach ($this->lines->recipients($line) as $recipient)
        {
            $messageStatus = $message->status($recipient);
            $messageStatus->markAsUnread();
        }
    }
}

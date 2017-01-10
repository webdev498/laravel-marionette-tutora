<?php namespace App\Events;

use App\Message;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class MessageWasOpened extends Event
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

}

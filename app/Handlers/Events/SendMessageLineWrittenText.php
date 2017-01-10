<?php

namespace App\Handlers\Events;

use Illuminate\Queue\QueueManager as Queue;
use App\Twilio\UserTwilio;
use App\Mailers\UserMailer;
use App\Events\MessageLineWasWritten;
use Illuminate\Auth\AuthManager as Auth;
use App\Repositories\Contracts\MessageLineRepositoryInterface;

class SendMessageLineWrittenText extends EventHandler
{

    /**
     * @var Queue
     */
    protected $queue;

    /**
     * @var Twilio
     */
    protected $twilio;

    /**
     * Create the event handler.
     *
     * @param  Queue $queue
     * @return void
     */
    public function __construct(Queue $queue, UserTwilio $twilio)
    {
        $this->queue = $queue;
        $this->twilio = $twilio;
    }


    public function handle(MessageLineWasWritten $event)
    {
        $line         = $event->line;
        $id           = $line->id;
        $message      = $line->message;
        $relationship = $message->relationship;
        $recipients   = [
            $relationship->tutor,
            $relationship->student,
        ];

        if ($line->user) {
            $recipients = array_filter($recipients, function ($user) use ($line) {
                return $user->id !== $line->user->id;
            });
        }

        foreach ($recipients as $recipient) {       
            
            $messageLines = app(MessageLineRepositoryInterface::class);
            $line = $messageLines->findById($id);

            $this->twilio->sendMessageLineWasWrittenText($recipient, $line);
        
        };
            
    }

}
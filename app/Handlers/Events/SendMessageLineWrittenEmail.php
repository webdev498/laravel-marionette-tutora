<?php

namespace App\Handlers\Events;

use App\Mailers\UserMailer;
use App\Events\MessageLineWasWritten;
use Illuminate\Auth\AuthManager as Auth;

class SendMessageLineWrittenEmail extends EventHandler
{

    /**
     * @var UserMailer
     */
    protected $mailer;

    /**
     * Create the event handler.
     *
     * @param  UserMailer $mailer
     * @return void
     */
    public function __construct(UserMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  MessageLineWasWritten $event
     * @return void
     */
    public function handle(MessageLineWasWritten $event)
    {
        $line         = $event->line;
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
            $this->mailer->sendMessageLineWasWrittenEmail($recipient, $line);
        }
    }

}

<?php

namespace App\Handlers\Events;

use App\Mailers\UserMailer;
use App\Events\ApplicationMessageLineWasWritten;
use Illuminate\Auth\AuthManager as Auth;

class SendApplicationMessageLineWrittenEmail extends EventHandler
{

    /**
     * @var UserMailer
     */
    protected $mailer;

    /**
     * Create the event handler.
     *
     * @param  UserMailer $mailer
     */
    public function __construct(UserMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  ApplicationMessageLineWasWritten $event
     *
     * @return void
     */
    public function handle(ApplicationMessageLineWasWritten $event)
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
            $this->mailer->sendApplicationMessageLineWasWrittenEmail($recipient, $line);
        }
    }

}

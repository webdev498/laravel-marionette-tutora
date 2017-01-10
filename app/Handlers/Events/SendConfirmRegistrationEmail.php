<?php namespace App\Handlers\Events;

use App\Mailers\UserMailer;
use App\Events\UserWasRegistered;

class SendConfirmRegistrationEmail extends EventHandler
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
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        $this->mailer->sendConfirmRegistrationEmail($event->user);
    }

}

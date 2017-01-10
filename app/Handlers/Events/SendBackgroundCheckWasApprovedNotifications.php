<?php namespace App\Handlers\Events;

use App\Events\BackgroundAdminStatusWasMadeApproved;
use App\Mailers\BackgroundCheckMailer;

class SendBackgroundCheckWasApprovedNotifications extends EventHandler
{

    /**
     * @var BackgroundCheckMailer
     */
    protected $mailer;

    /**
     * @param BackgroundCheckMailer $mailer
     */
    public function __construct(BackgroundCheckMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  BackgroundAdminStatusWasMadeApproved  $event
     *
     * @return void
     */
    public function handle(BackgroundAdminStatusWasMadeApproved $event)
    {
        $background = $event->background;

        $this->mailer->adminStatusApproved($background);

    }

}

<?php namespace App\Handlers\Events;

use App\Events\BackgroundAdminStatusWasMadeRejected;
use App\Mailers\BackgroundCheckMailer;

class SendBackgroundCheckWasRejectedNotifications extends EventHandler
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
     * @param  BackgroundAdminStatusWasMadeRejected  $event
     *
     * @return void
     */
    public function handle(BackgroundAdminStatusWasMadeRejected $event)
    {
        $background = $event->background;

        $this->mailer->adminStatusRejected($background);

    }

}

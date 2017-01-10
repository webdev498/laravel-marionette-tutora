<?php

namespace App\Handlers\Events;

use App\Mailers\TutorMailer;
use App\Events\TutorNoResponseLimitReached;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNoResponseLimitReachedEmail
{
    private $mailer;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(TutorMailer $mailer)
    {
        //
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  TutorNoResponseLimitReached  $event
     * @return void
     */
    public function handle(TutorNoResponseLimitReached $event)
    {
        $tutor = $event->tutor;

        $this->mailer->noResponseLimitReached($tutor);
    }
}

<?php

namespace App\Handlers\Events;

use App\Events\LessonBookingTransferFailed;
use App\Mailers\TutorAccountMailer;

class SendTheTransferFailedNotifications extends EventHandler
{
    /**
     * @var TutorAccountMailer
     */
    protected $mailer;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(TutorAccountMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingTransferFailed  $event
     * @return void
     */
    public function handle(LessonBookingTransferFailed $event)
    {
        $booking = $event->booking;
        $lesson = $booking->lesson;
        $relationship = $lesson->relationship;
        $tutor = $relationship->tutor;

        $this->mailer->transferFailed($tutor);

    }
}

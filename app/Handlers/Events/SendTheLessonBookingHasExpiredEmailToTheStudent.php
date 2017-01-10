<?php namespace App\Handlers\Events;

use App\Mailers\StudentsLessonMailer;
use App\Events\LessonBookingHasExpired;

class SendTheLessonBookingHasExpiredEmailToTheStudent extends EventHandler
{

    /**
     * @var StudentsLessonMailer
     */
    protected $mailer;

    /**
     * Create the event handler.
     *
     * @param  StudentsLessonMailer $mailer
     * @return void
     */
    public function __construct(StudentsLessonMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingHasExpired $event
     * @return void
     */
    public function handle(LessonBookingHasExpired $event)
    {
        $booking      = $event->booking;
        $lesson       = $booking->lesson;
        $relationship = $lesson->relationship;
        $student      = $relationship->student;

        $this->mailer->lessonExpired($student, $relationship, $lesson, $booking);
    }

}

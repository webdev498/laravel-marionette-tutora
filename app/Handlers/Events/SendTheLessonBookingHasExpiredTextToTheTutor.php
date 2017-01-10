<?php namespace App\Handlers\Events;

use App\Twilio\TutorTwilio;
use App\Events\LessonBookingHasExpired;

class SendTheLessonBookingHasExpiredTextToTheTutor extends EventHandler
{

    /**
     * @var StudentsLessonMailer
     */
    protected $twilio;

    /**
     * Create the event handler.
     *
     * @param  StudentsLessonMailer $mailer
     * @return void
     */
    public function __construct(TutorTwilio $twilio)
    {
        $this->twilio = $twilio;
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
        $tutor        = $relationship->tutor;

        $this->twilio->lessonExpired($tutor, $relationship, $lesson, $booking);
    }

}

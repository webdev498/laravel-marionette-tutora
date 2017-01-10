<?php namespace App\Handlers\Events;

use App\Twilio\StudentTwilio;
use App\Events\LessonBookingHasExpired;

class SendTheLessonBookingHasExpiredTextToTheStudent extends EventHandler
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
    public function __construct(StudentTwilio $twilio)
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
        $student      = $relationship->student;

        $this->twilio->lessonExpired($student, $relationship, $lesson, $booking);
    }

}

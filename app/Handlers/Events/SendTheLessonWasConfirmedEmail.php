<?php namespace App\Handlers\Events;

use App\LessonBooking;
use App\Events\LessonWasConfirmed;
use App\Mailers\TutorsLessonMailer;
use App\Mailers\StudentsLessonMailer;

class SendTheLessonWasConfirmedEmail extends EventHandler
{
    /**
     * @var StudentsLessonMailer
     */
    protected $studentMailer;

    /**
     * @var TutorsLessonMailer;
     */
    protected $tutorMailer;

    /**
     * Create the event handler.
     *
     * @param  StudentsLessonMailer $studentMailer
     * @param  TutorsLessonMailer   $tutorMailer
     * @return void
     */
    public function __construct(
        StudentsLessonMailer $studentMailer,
        TutorsLessonMailer   $tutorMailer
    ) {
        $this->studentMailer = $studentMailer;
        $this->tutorMailer   = $tutorMailer;
    }

    /**
     * Handle the event.
     *
     * @param  LessonWasConfirmed $event
     * @return void
     */
    public function handle(LessonWasConfirmed $event)
    {
        $lesson       = $event->lesson;
        $booking      = $lesson->bookings->first();
        $relationship = $lesson->relationship;

        $this->studentMailer->lessonConfirmed($relationship->student, $relationship, $lesson, $booking);
        $this->tutorMailer->lessonConfirmed($relationship->tutor, $relationship, $lesson, $booking);
    }
}

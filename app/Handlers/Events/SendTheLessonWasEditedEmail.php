<?php namespace App\Handlers\Events;

use App\Events\LessonWasEdited;
use App\Mailers\StudentsLessonMailer;

class SendTheLessonWasEditedEmail extends EventHandler
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
     * @param  LessonWasEdited $event
     * @return void
     */
    public function handle(LessonWasEdited $event)
    {
        $lesson       = $event->lesson;
        $relationship = $lesson->relationship;
        $student      = $relationship->student;

        $this->mailer->lessonEdited($student, $relationship, $lesson);
    }

}

<?php 

namespace App\Handlers\Events;

use App\Lesson;
use App\Events\LessonWasConfirmed;
use App\Twilio\StudentTwilio;
use App\Twilio\TutorTwilio;

class SendTheLessonWasConfirmedText extends EventHandler
{
	
    /**
     * @var studentTwilio
     */
    protected $studentTwilio;

    /**
     * @var tutorTwilio
     */
    protected $tutorTwilio;

    /**
     * Create the event handler.
     *
     * @param  studentTwilio   $studentTwilio
     * @return void
     */
	public function __construct(StudentTwilio $studentTwilio, TutorTwilio $tutorTwilio)
	{
		$this->studentTwilio = $studentTwilio;
		$this->tutorTwilio = $tutorTwilio;
	}

	public function handle(LessonWasConfirmed $event)
	{
		$lesson = $event->lesson;
		$relationship = $lesson->relationship;
        $student = $relationship->student;
        $tutor = $relationship->tutor;

		$this->studentTwilio->lessonConfirmed($student, $lesson, $relationship);
		$this->tutorTwilio->lessonConfirmed($tutor, $lesson, $relationship);

	}
}
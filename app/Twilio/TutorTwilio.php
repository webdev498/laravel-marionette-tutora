<?php namespace App\Twilio;

use App\Lesson;
use App\LessonBooking;
use App\MessageLine;
use App\Presenters\RelationshipPresenter;
use App\Relationship;
use App\Student;
use App\Tutor;

class TutorTwilio extends AbstractTwilio
{

	public function lessonConfirmed(
		Tutor $tutor,
		Lesson $lesson, 
		Relationship $relationship
	) {

		$message = "Your lesson with {$relationship->student->first_name} has been confirmed. {$relationship->student->first_name}'s number is {$relationship->student->telephone} should you need to get in touch. Please ensure you have their address.";

		$this->sendToUser($tutor, $message);
	}

	public function lessonExpired(
		Tutor $tutor, 
		Relationship $relationship, 
		Lesson $lesson, 
		LessonBooking $booking
	) {
		$message = "Hi {$tutor->first_name}, {$relationship->student->first_name} has not confirmed the lesson in time, and it has been cancelled. Please get in touch with them if you would like to rearrange.";

		$this->sendToUser($tutor, $message);
	}

	public function lessonRebook(
        Tutor         $tutor,
        Relationship  $relationship,
        Lesson        $lesson,
        LessonBooking $booking
	) {
		$url = route('tutor.lessons.create', ['student' => $relationship->student->uuid]);

		$message = "Hi {$tutor->first_name}, we hope your lesson with {$relationship->student->first_name} went well. Please book in your next lesson (try to book recurring lessons) using the link below:"
		. "\n"
		. "{$url}";
		$this->sendToUser($tutor, $message);
	}

	public function noReplyToMessageLineFirstReminder(
		MessageLine $line, 
		Tutor $tutor, 
		Student $student,
		Relationship $relationship
	) {

		$url = route('message.redirect', [
        	'uuid' => $line->message->uuid,
	    ]);


		$message = "Hi {$tutor->first_name}, an enquiry from {$student->first_name} is still awaiting a reply. To reply, click the link below:"
		."\n"
		. "{$url}";

		$this->sendToUser($tutor, $message);
	}

	public function noReplyToMessageLineSecondReminder(
		MessageLine $line, 
		Tutor $tutor, 
		Student $student,
		Relationship $relationship
	) {

		$url = route('message.redirect', [
        	'uuid' => $line->message->uuid,
	    ]);


		$message = "Hi {$tutor->first_name}, it's been 5 days since {$student->first_name} asked if you can help. If you do not have space for more students, please take your profile offline.";

		$this->sendToUser($tutor, $message);
	}
}
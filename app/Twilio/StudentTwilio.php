<?php namespace App\Twilio;

use App\Lesson;
use App\LessonBooking;
use App\MessageLine;
use App\Presenters\RelationshipPresenter;
use App\Relationship;
use App\Student;
use App\Tutor;

class StudentTwilio extends AbstractTwilio
{
	
    /**
     * Send an email to the student regarding a lesson being booked.
     *
     * @param  Student       $student
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @return void
     */

	public function lessonBooked(
		Student 		$student, 
		Relationship	$relationship, 
		Lesson 			$lesson, 
		LessonBooking	$booking
	) {
		$relationship = $this->presentItem($relationship, new RelationshipPresenter(), [
			'include' => [
			    'tutor',
			    'student',
			],
		
		]);
		$name = $student->first_name;
		$tutor = $relationship->tutor;
		$url = route('student.lessons.confirm', [
	        'booking' => $booking->uuid
	    ]);

		$message = "Hi {$student->first_name}, {$tutor->first_name} has booked a lesson. Please confirm the booking by entering your payment details:"
		. "\n"
		. "{$url}";
		
		$this->sendToUser($student, $message);
	}


	public function lessonConfirmed(
		Student $student,
		Lesson $lesson, 
		Relationship $relationship
	) {
		$message = "Thanks for confirming your lesson. {$relationship->tutor->first_name}'s number is {$relationship->tutor->telephone} should you need to get in touch. Please send them your full address.";

		$this->sendToUser($student, $message);
	}

	public function lessonExpired(
		Student $student, 
		Relationship $relationship, 
		Lesson $lesson, 
		LessonBooking $booking
	) {
		$message = "Hi {$student->first_name}, your lesson with {$relationship->tutor->first_name} was not confirmed in time, and has been cancelled. Please call us on 01143830989 if you would like to rearrange.";

		$this->sendToUser($student, $message);
	}

	public function lessonStillPending(
        Student       $student,
        Relationship  $relationship,
        Lesson        $lesson,
        LessonBooking $booking
	) {

		$url = route('student.lessons.confirm', [
	        'booking' => $booking->uuid
	    ]);

		$message = "Hi {$student->first_name}, you still haven't confirmed your upcoming lesson with {$relationship->tutor->first_name}. Please confirm it by entering your payment details below"
		. "\n"
		. "{$url}";
		
		$this->sendToUser($student, $message);
	}

	public function lessonRebook(
        Student       $student,
        Relationship  $relationship,
        Lesson        $lesson,
        LessonBooking $booking
	) {
		$url = route('message.redirect', [
        	'uuid' => $relationship->message->uuid,
	    ]);

		$message = "Hi {$student->first_name}, we hope you enjoyed your lesson with {$relationship->tutor->first_name}. If you would like another lesson, please ask them to book it in for you."
		. "\n"
		. "{$url}";
		$this->sendToUser($student, $message);
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


		$message = "Hi {$student->first_name}, your tutor {$tutor->first_name} has said they are able to help with lessons. To read their message, follow the link below:"
		."\n"
		. "{$url}";

		$this->sendToUser($student, $message);
	}
}
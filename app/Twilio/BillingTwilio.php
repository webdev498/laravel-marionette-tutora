<?php namespace App\Twilio;

use App\Lesson;
use App\Student;
use App\Tutor;
use App\Relationship;
use App\LessonBooking;

class BillingTwilio extends AbstractTwilio 
{
	public function firstChargeAttemptFailedToStudent(
		Student 		$student, 
		Tutor 			$tutor, 
		Relationship	$relationship, 
		LessonBooking 	$booking
	) {

		$message = "Hi {$student->first_name}, this is just a reminder to make sure there are funds available "
                    ."for your upcoming lesson with {$relationship->tutor->first_name} on "
                    .$booking->start_at->format('l \t\h\e jS').".\n\n"
                    ."If you need to, re-enter your payment details at "
                    .route('student.account.payment.index')."\n\n"
                    ."Thanks, Tutora";
        $this->sendToUser($student, $message);

	}

	public function secondChargeAttemptFailedToStudent(
		Student 		$student, 
		Tutor 			$tutor, 
		Relationship	$relationship, 
		LessonBooking 	$booking
	) {

		$number = config('contact.phone');

		$message = "Hi {$student->first_name}, we haven't been able to charge your card for your lesson yesterday with  {$relationship->tutor->first_name}. We will retry the payment shortly. Please get in touch with us on $number to arrange payment. You can also update your card details at "
                    .route('student.account.payment.index')."\n\n"
                    ."Thanks, Tutora";
        $this->sendToUser($student, $message);

	}

	public function thirdChargeAttemptFailedToStudent(
		Student 		$student, 
		Tutor 			$tutor, 
		Relationship	$relationship, 
		LessonBooking 	$booking
	) {

		$number = config('contact.phone');

		$message = "Hi {$student->first_name}, it's been {$booking->start_at->diffForHumans(null, true)} since your lesson, and we've still not been able to charge the card you have given us. If we are not able to collect payment shortly, we may stop any future lessons taking place.\n\n Call us on $number to update your details, or do it online at: "
                    .route('student.account.payment.index')."\n\n"
                    ."Thanks, Tutora";
        $this->sendToUser($student, $message);

	}

	public function forthChargeAttemptFailedToStudent(
		Student 		$student, 
		Tutor 			$tutor, 
		Relationship	$relationship, 
		LessonBooking 	$booking
	) {

		$number = config('contact.phone');

		$message = "Hi {$student->first_name}, it's been {$booking->start_at->diffForHumans(null, true)} since your lesson, and we've still not been able to charge the card you have given us. If we are not able to collect payment shortly, we may stop any future lessons taking place.\n\n Call us on $number to update your details, or do it online at: "
                    .route('student.account.payment.index')."\n\n"
                    ."Thanks, Tutora";
        $this->sendToUser($student, $message);

	}


}
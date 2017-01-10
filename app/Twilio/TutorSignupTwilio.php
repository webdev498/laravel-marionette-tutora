<?php namespace App\Twilio;

use App\Tutor;
use App\UserRequirementCollection;
use App\UserSubscription;

class TutorSignupTwilio extends AbstractTwilio
{
	public function firstSignupReminder(
		Tutor $tutor,
		UserRequirementCollection $requirements
	) {
		$url = route('tutor.profile.show', ['uuid' => $tutor->uuid]);

		$list = UserSubscription::SIGNUP;

		$message = "Hi {$tutor->first_name}, thanks for signing up to tutor with us at Tutora yesterday. Please complete your profile at:"
		. "\n"
		. "{$url}";

		$this->sendToUser($tutor, $message, $list);
	}

	public function secondSignupReminder(
		Tutor $tutor,
		UserRequirementCollection $requirements
	) {

		$url = route('tutor.profile.show', ['uuid' => $tutor->uuid]);

		$list = UserSubscription::SIGNUP;

		$message = "Hi {$tutor->first_name}, it’s been a few days since you signed up and there are parts of your profile still to complete:"
		. "\n"
		. "{$url}";

		$this->sendToUser($tutor, $message, $list);
	}

	public function thirdSignupReminder(
		Tutor $tutor,
		UserRequirementCollection $requirements
	) {

		$url = route('tutor.profile.show', ['uuid' => $tutor->uuid]);

		$list = UserSubscription::SIGNUP;

		$message = "Hi {$tutor->first_name}, our students are still waiting for you at Tutora! Just log in to complete your profile:"
		. "\n"
		. "{$url}";

		$this->sendToUser($tutor, $message, $list);
	}

	public function finalSignupReminder(
		Tutor $tutor,
		UserRequirementCollection $requirements
	) {

		$url = route('tutor.profile.show', ['uuid' => $tutor->uuid]);

		$list = UserSubscription::SIGNUP;

		$message = "Hi {$tutor->first_name}, your tutoring application will expire in 7 days. Click the link below to complete your profile:"
		. "\n"
		. "{$url}";

		$this->sendToUser($tutor, $message, $list);
	}

	public function firstIdentificationSignupReminder(
		Tutor $tutor,
		UserRequirementCollection $requirements
	) {

		$url = route('tutor.profile.show', ['uuid' => $tutor->uuid]);

		$list = UserSubscription::SIGNUP;

		$message = "Hi {$tutor->first_name}, we just need a copy of your ID to get you started Tutoring. Upload a passport or driver license using the link:"
		. "\n"
		. "{$url}";

		$this->sendToUser($tutor, $message, $list);
	}

	public function secondIdentificationSignupReminder(
		Tutor $tutor,
		UserRequirementCollection $requirements
	) {

		$url = route('tutor.profile.show', ['uuid' => $tutor->uuid]);

		$list = UserSubscription::SIGNUP;

		$message = "Hi {$tutor->first_name}, we still need a copy of your ID to get you started. Please upload your ID using the link below:"
		. "\n"
		. "{$url}";

		$this->sendToUser($tutor, $message, $list);
	}

	public function thirdIdentificationSignupReminder(
		Tutor $tutor,
		UserRequirementCollection $requirements
	) {

		$url = route('tutor.profile.show', ['uuid' => $tutor->uuid]);

		$list = UserSubscription::SIGNUP;

		$message = "Hi {$tutor->first_name}, please upload a copy of your ID to your account, or send it to us at support@tutora.co.uk:"
		. "\n"
		. "{$url}";

		$this->sendToUser($tutor, $message, $list);
	}
}
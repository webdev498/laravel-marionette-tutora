<?php 

namespace App\Schedules\TutorSignupNotifications;

use App\Tutor;
use Carbon\Carbon;
use App\UserRequirement;
use App\Mailers\TutorSignupMailer;
use App\Twilio\TutorSignupTwilio;

abstract class AbstractSignupNotification
{
	private $mailer;
	private $twilio;
	public $tutor;
	public $requirements;

	public function __construct(TutorSignupMailer $mailer, TutorSignupTwilio $twilio)
	{
		$this->mailer = $mailer;
		$this->twilio = $twilio;
	}
	
	public function setProperties($tutor)
	{
		$this->tutor = $tutor;
		$this->setRequirements();
	}

	public function setRequirements()
	{
		$this->requirements = $this->tutor->requirements;
	}
	
	public function send()
	{
		
		$this->mailer->{$this->emailMethod}($this->tutor, $this->requirements);
		$this->twilio->{$this->textMethod}($this->tutor, $this->requirements);
	}

	protected function instantiateNotification($notificationClassName)
	{
		$notificationClassName = __NAMESPACE__ . "\\". $notificationClassName;

		$notification = app($notificationClassName);
		return $notification;
	}
	
	protected function getNow()
	{
		return Carbon::now();
	}

}
<?php 

namespace App\Schedules\StudentMarketingNotifications;

use App\Mailers\StudentMarketingMailer;
use App\Tutor;
use App\UserRequirement;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesCommands;
use App\Commands\SearchResultsForMarketingCommand;

abstract class AbstractMarketingNotification
{
	
	use DispatchesCommands;

	protected $mailer;
	protected $student;
	protected $tutors;

	public function __construct(
		StudentMarketingMailer $mailer
	)
	{
		$this->mailer = $mailer;
	}
	
	public function setProperties($student)
	{
		$this->student = $student;
		$this->schedule = $student->schedule;
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
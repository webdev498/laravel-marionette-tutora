<?php

namespace App\Schedules\TutorSignupNotifications;

use App\UserRequirementCollection;
use App\UserRequirement;
use App\Schedules\Contracts\NotificationInterface;

class FirstSignupNotification extends AbstractSignupNotification implements NotificationInterface
{

	protected $emailMethod = 'firstSignupReminder';
	protected $textMethod = 'firstSignupReminder';

	public function getNextNotification()
	{
		if ($this->requirements->onlyPending(UserRequirement::IDENTIFICATION)) {
			return $this->instantiateNotification('FirstIdentificationNotification');
		}

		return $this->instantiateNotification('SecondSignupNotification');
	}

	public function action($schedule)
	{
		
		// Action
		$this->send();

		// Update Schedule
		$schedule->send_at =  socialise_time($this->getNow()->addDays(2), 'pm');
		$schedule->last_notification_name = basename(str_replace('\\', '/', get_class($this)));;
		$schedule->count++;
		$schedule->save();
	}
}
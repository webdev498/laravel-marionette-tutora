<?php

namespace App\Schedules\TutorSignupNotifications;


use App\UserRequirement;
use App\Schedules\Contracts\NotificationInterface;

class SecondSignupNotification extends AbstractSignupNotification implements NotificationInterface
{

	

	protected $emailMethod = 'secondSignupReminder';
	protected $textMethod = 'secondSignupReminder';

	public function getNextNotification()
	{
		if ($this->requirements->onlyPending(UserRequirement::IDENTIFICATION)) {
			return $this->instantiateNotification('FirstIdentificationNotification');
		}

		return $this->instantiateNotification('ThirdSignupNotification');
	}

	public function action($schedule)
	{
		// Notify
		$this->send();

		// Schedule Update
		$schedule->last_notification_name = basename(str_replace('\\', '/', get_class($this)));;
		$schedule->count++;
		$schedule->send_at = socialise_time($this->getNow()->addDays(4), 'pm');
		$schedule->save();
	}
}
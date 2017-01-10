<?php

namespace App\Schedules\TutorSignupNotifications;
use App\UserRequirement;
use App\Schedules\Contracts\NotificationInterface;

class ThirdSignupNotification extends AbstractSignupNotification implements NotificationInterface
{

	protected $emailMethod = 'thirdSignupReminder';
	protected $textMethod = 'thirdSignupReminder';

	public function getNextNotification()
	{
		if ($this->requirements->onlyPending(UserRequirement::IDENTIFICATION)) {
			return $this->instantiateNotification('SecondIdentificationNotification');
		}

		return $this->instantiateNotification('FinalSignupNotification');
	}

	public function action($schedule)
	{
		
		$this->send();

		$schedule->last_notification_name = basename(str_replace('\\', '/', get_class($this)));;
		$schedule->count++;
		$schedule->send_at = socialise_time($this->getNow()->addDays(7), 'pm');
		$schedule->save();
	}
}
<?php

namespace App\Schedules\TutorSignupNotifications;

use App\Schedules\Contracts\NotificationInterface;
use App\Schedules\TutorSignupNotifications\AbstractSignupNotification;
use App\UserRequirement;

class ThirdIdentificationNotification extends AbstractSignupNotification implements NotificationInterface
{

	protected $emailMethod = 'thirdIdentificationSignupReminder';
	protected $textMethod = 'thirdIdentificationSignupReminder';

	public function getNextNotification()
	{
		return $this->instantiateNotification('FinalSignupNotification');
	}


	public function action($schedule)
	{
		
		// Notify
		$this->send();

		// Action
		$schedule->last_notification_name = basename(str_replace('\\', '/', get_class($this)));;
		$schedule->count++;
		$schedule->send_at = socialise_time($this->getNow()->addDays(7), 'pm');
		$schedule->save();
	}
}
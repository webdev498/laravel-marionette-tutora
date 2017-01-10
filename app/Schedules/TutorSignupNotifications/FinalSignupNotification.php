<?php

namespace App\Schedules\TutorSignupNotifications;

use App\UserRequirement;
use App\Schedules\Contracts\NotificationInterface;

class FinalSignupNotification extends AbstractSignupNotification implements NotificationInterface
{

	protected $name = 'FinalSignupNotification';

	protected $emailMethod = 'finalSignupReminder';
	protected $textMethod = 'finalSignupReminder';

	public function getNextNotification()
	{
		return $this->instantiateNotification('ExpireNotification');
	}

	public function action($schedule)
	{
		
		// Action
		$this->send();

		// Schedule
		$schedule->send_at = socialise_time($this->getNow()->addDays(7), 'pm');
		$schedule->count++;
		$schedule->last_notification_name = basename(str_replace('\\', '/', get_class($this)));;
		$schedule->save();
	}
}
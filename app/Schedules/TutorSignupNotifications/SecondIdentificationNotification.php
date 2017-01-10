<?php

namespace App\Schedules\TutorSignupNotifications;

use App\Schedules\Contracts\NotificationInterface;
use App\Schedules\TutorSignupNotifications\AbstractSignupNotification;
use App\UserRequirement;

class SecondIdentificationNotification extends AbstractSignupNotification implements NotificationInterface
{

	protected $emailMethod = 'secondIdentificationSignupReminder';
	protected $textMethod = 'secondIdentificationSignupReminder';


	public function getNextNotification()
	{
		return $this->instantiateNotification('ThirdIdentificationNotification');
	}

	public function action($schedule)
	{
		// Notify
		$this->send();

		// Schedule
		$schedule->last_notification_name = basename(str_replace('\\', '/', get_class($this)));;
		$schedule->count++;
		$schedule->send_at = socialise_time($this->getNow()->addDays(4), 'pm');
		$schedule->save();
	}
}
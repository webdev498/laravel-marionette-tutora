<?php

namespace App\Schedules\TutorSignupNotifications;

use App\Schedules\Contracts\NotificationInterface;
use App\Schedules\TutorSignupNotifications\AbstractSignupNotification;
use App\UserRequirement;

class FirstIdentificationNotification extends AbstractSignupNotification implements NotificationInterface
{
	protected $emailMethod = 'firstIdentificationSignupReminder';
	protected $textMethod = 'firstIdentificationSignupReminder';

	public function getNextNotification()
	{
		return $this->instantiateNotification('SecondIdentificationNotification');
	}

	public function action($schedule)
	{
		$this->send();
		$schedule->last_notification_name = basename(str_replace('\\', '/', get_class($this)));;
		$schedule->count++;
		$schedule->send_at = socialise_time($this->getNow()->addDays(3), 'pm');
		$schedule->save();
	}
}
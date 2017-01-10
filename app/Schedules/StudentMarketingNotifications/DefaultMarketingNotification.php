<?php

namespace App\Schedules\StudentMarketingNotifications;

use App\Schedules\Contracts\NotificationInterface;
use App\Schedules\StudentMarketingSchedule;

class DefaultMarketingNotification extends AbstractMarketingNotification implements NotificationInterface
{
	

	public function getNextNotification()
	{
		return $this->instantiateNotification('FirstMarketingNotification');
	}

	public function action($schedule)
	{
		// Send
		

		// Schedule
		$date = day_from_date($this->getNow()->addDays(7), 'Tuesday', 'pm');
		$schedule->count++;
		$schedule->last_notification_name = basename(str_replace('\\', '/', get_class($this)));;
		$schedule->save();
	}	
}
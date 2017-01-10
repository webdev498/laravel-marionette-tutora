<?php

namespace App\Schedules;

use App\Student;
use Carbon\Carbon;

class StudentMarketingSchedule extends NotificationSchedule
{
	const TYPE = 'StudentMarketing';
	
	const INITIAL_NOTIFICATION_TYPE = 'DefaultMarketingNotification';

	protected $notificationsFolder = 'StudentMarketingNotifications';

	public static function initialise()
	{
		$scheduler = new static();
		$scheduler->type = static::TYPE;
		$scheduler->last_notification_name = static::INITIAL_NOTIFICATION_TYPE;
		$scheduler->send_at = socialise_time(Carbon::now()->modify('+1 week'), 'am');
		return $scheduler;
	}

}

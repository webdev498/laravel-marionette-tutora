<?php

namespace App\Schedules;

use App\Tutor;
use Carbon\Carbon;

class TutorJobsSchedule extends NotificationSchedule
{
	const TYPE = 'TutorJobs';
	
	const INITIAL_NOTIFICATION_TYPE = 'DefaultJobsNotification';

	protected $notificationsFolder = 'TutorJobsNotifications';

	public static function initialise()
	{
		$scheduler = new static();
		$scheduler->type = static::TYPE;
		$scheduler->last_notification_name = static::INITIAL_NOTIFICATION_TYPE;
		$scheduler->send_at = Carbon::now()->addDays(1)->hour(config('jobs.send_at_hour'));

		return $scheduler;
	}

}

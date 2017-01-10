<?php

namespace App\Schedules;

use App\Tutor;

class TutorSignupSchedule extends NotificationSchedule
{
    const TYPE = 'TutorSignup';
    const INITIAL_NOTIFICATION_TYPE = 'DefaultSignupNotification';

    protected $notificationsFolder = 'TutorSignupNotifications';

	public static function initialise()
	{
		$scheduler = new static();
		$scheduler->type = static::TYPE;
		$scheduler->last_notification_name = static::INITIAL_NOTIFICATION_TYPE;
		$scheduler->send_at = socialise_time(\Carbon\Carbon::now()->addDays(1), 'pm');
		return $scheduler;
	}


}

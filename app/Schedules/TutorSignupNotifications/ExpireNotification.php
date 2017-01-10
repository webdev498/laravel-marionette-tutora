<?php

namespace App\Schedules\TutorSignupNotifications;

use App\Schedules\Contracts\NotificationInterface;
use App\UserProfile;
use App\UserRequirement;

class ExpireNotification extends AbstractSignupNotification implements NotificationInterface
{

	protected $emailMethod = 'finalSignupReminder';
	protected $textMethod = 'finalSignupReminder';


	public function getNextNotification()
	{
		return false;
	}
	

	public function action($schedule)
	{
		
		// Action
		$tutor = $this->tutor;
		$tutor = UserProfile::expire($tutor->profile);
		$tutor->save();

		$schedule->delete();		
	}
}
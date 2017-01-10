<?php

namespace App\Schedules\TutorSignupNotifications;

use App\UserRequirement;
use App\Schedules\Contracts\NotificationInterface;

class DefaultSignupNotification extends AbstractSignupNotification implements NotificationInterface
{

	public function getNextNotification()
	{
		if ($this->requirements->onlyPending(UserRequirement::IDENTIFICATION)) {
			return $this->instantiateNotification('FirstIdentificationNotification');
		}

		return $this->instantiateNotification('FirstSignupNotification');
	}

	public function action($schedule)
	{
	}
}
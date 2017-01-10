<?php

namespace App\Schedules\Contracts;

use App\Schedules\NotificationSchedule;

interface NotificationInterface
{
	public function getNextNotification();

	public function action($schedule);
}
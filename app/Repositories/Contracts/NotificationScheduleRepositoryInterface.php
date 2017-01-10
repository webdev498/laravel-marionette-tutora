<?php namespace App\Repositories\Contracts;

use App\Message;
use App\Schedules\NotificationSchedule;
use App\Tutor;
use App\User;
use DateTime;

interface NotificationScheduleRepositoryInterface
{

	public function saveForUser(User $user, NotificationSchedule $schedule);

	public function getBySendAt(DateTime $date);

	public function delete(NotificationSchedule $schedule);
}
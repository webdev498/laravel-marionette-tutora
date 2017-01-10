<?php namespace App\Repositories;

use DateTime;
use App\Repositories\Contracts\NotificationScheduleRepositoryInterface;
use App\Schedules\NotificationSchedule;
use App\Tutor;
use App\User;

class EloquentNotificationScheduleRepository implements NotificationScheduleRepositoryInterface
{
	protected $schedule;

	public function __construct(NotificationSchedule $schedule)
	{
		$this->schedule = $schedule;
	}

	public function saveForUser(User $user, NotificationSchedule $schedule)
	{
		return $user->schedules()->save($schedule);
	}

    public function getBySendAt(DateTime $date)
    {
        return $this->schedule
            ->where('send_at', '<=', $date)
            ->get();
    }

    public function queryBySendAt(DateTime $date)
    {
        return $this->schedule
            ->where('send_at', '<=', $date);
    }


    public function delete(NotificationSchedule $schedule)
    {
    	return $schedule->delete();
    }

}
<?php

namespace App\Schedules\TutorJobsNotifications;

use App\User;
use App\Tutor;
use App\Job;
use App\Address;
use App\UserProfile;
use App\Search\JobSearcher;
use App\Schedules\NotificationSchedule;
use App\Schedules\Contracts\NotificationInterface;

class DefaultJobsNotification extends AbstractJobsNotification implements NotificationInterface
{

	public function getNextNotification()
	{
		return $this->instantiateNotification('DefaultJobsNotification');
	}

	public function action($schedule)
	{
		$profile 	= $this->tutor->profile;

		if(! $this->calc->isEligible($this->tutor)) return;

		$sort 	= JobSearcher::SORT_DATE_CREATED;
		$filter = JobSearcher::FILTER_SUBJECTS;
		$date	= $this->getNow()->subDays(config('jobs.jobs_newer_than_period'));

		// Send
		list($jobs, $count) = $this->searcher->searchForTutor(
			$this->tutor,
			$date,
			$sort,
			$filter,
			true
		);

		if($count > 0) {
			$this->mailer->newJobsForTutor($this->tutor, $jobs);
		}

		$currentDate = $this->getNow();
		$nextDate 	 = $this->getNow()->addDays(1)->hour(config('jobs.send_at_hour'));

		// Update schedule
		$schedule->last_notification_name = basename(str_replace('\\', '/', get_class($this)));;
		$schedule->count++;
		$schedule->send_at = $nextDate;
		$schedule->last_sent_at = $currentDate;
		$schedule->save();
	}	
}
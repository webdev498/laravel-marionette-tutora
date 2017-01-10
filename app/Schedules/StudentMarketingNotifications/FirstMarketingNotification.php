<?php

namespace App\Schedules\StudentMarketingNotifications;

use App\Commands\SearchResultsForMarketingCommand;
use App\Schedules\Contracts\NotificationInterface;
use App\Schedules\StudentMarketingSchedule;

class FirstMarketingNotification extends AbstractMarketingNotification implements NotificationInterface
{
	
	public function getNextNotification()
	{
		return $this->instantiateNotification('FirstMarketingNotification');
	}

	public function action($schedule)
	{
		// Send
		if ($this->student->status !== 'confirmed' && $this->student->status !== 'pending' && $this->student->status !== 'recurring') // only send to students who are not having lessons
		{

			if ($this->student->searches->count() !== 0) {
				// We have a search for student

				$search = $this->student->searches->last();
				$tutors = $this->dispatch(new SearchResultsForMarketingCommand($search, 3, 10));
				if ($tutors) {
					$this->mailer->studentMarketingWithSuggestions($this->student, $search, $tutors);
				}
				
			} else {
				// No search data for student. Send a generic email
				$tutors = $this->dispatch(new SearchResultsForMarketingCommand(null, 3, 20));
				$this->mailer->studentMarketingDefault($this->student, $tutors);
			}
		}

		
		// Update schedule
		
		if ($schedule->count >= 6) {
			$days = 28;
		} else {
			$days = 14;
		}
		
		
		$date = day_from_date($this->getNow()->addDays($days), 'Tuesday', 'pm');
		$schedule->last_notification_name = basename(str_replace('\\', '/', get_class($this)));;
		$schedule->count++;
		$schedule->send_at = $date;
		$schedule->save();
	}
}
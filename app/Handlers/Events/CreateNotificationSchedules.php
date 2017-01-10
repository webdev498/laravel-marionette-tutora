<?php

namespace App\Handlers\Events;

use App\Events\UserWasRegistered;
use App\Handlers\Events\EventHandler;
use App\Repositories\Contracts\NotificationScheduleRepositoryInterface;
use App\Schedules\StudentMarketingSchedule;
use App\Schedules\TutorSignupSchedule;
use App\Schedules\TutorJobsSchedule;
use App\Student;
use App\Tutor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateNotificationSchedules extends EventHandler
{
    protected $schedules;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(NotificationScheduleRepositoryInterface $schedules)
    {
        $this->schedules = $schedules;
    }

    /**
     * Handle the event.
     *
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        $user = $event->user;

        if ($user instanceof Tutor) {            
            // Insert
            $schedule = TutorSignupSchedule::initialise();
            $schedule = $this->schedules->saveForUser($user, $schedule);

            // New Jobs Notification
            $schedule = TutorJobsSchedule::initialise();
            $this->schedules->saveForUser($user, $schedule);
            
        }
 
        if ($user instanceof Student) {
            $studentMarketingSchedule = StudentMarketingSchedule::initialise();
            $this->schedules->saveForUser($user, $studentMarketingSchedule);
        }
    }
}

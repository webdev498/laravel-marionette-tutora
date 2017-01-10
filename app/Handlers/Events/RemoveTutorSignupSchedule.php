<?php

namespace App\Handlers\Events;

use App\Schedules\TutorSignupSchedule;
use App\Events\UserProfileWasSubmitted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\Contracts\NotificationScheduleRepositoryInterface;

class RemoveTutorSignupSchedule
{
    protected $schedules;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(NotificationScheduleRepositoryInterface $schedules)
    {
        //
        $this->schedules = $schedules;
    }

    /**
     * Handle the event.
     *
     * @param  UserProfileWasSubmitted  $event
     * @return void
     */
    public function handle(UserProfileWasSubmitted $event)
    {
        $profile = $event->profile;
        $tutor = $profile->tutor;
        $signupSchedule = $tutor->signupSchedule;

        if ($signupSchedule instanceof TutorSignupSchedule) {
            $this->schedules->delete($signupSchedule);
        }
    }
}

<?php

namespace App\Handlers\Events;

use App\Events\UserProfileWasMadeOffline;
use App\Reminder;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use App\UserProfile;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateGoOnlineReminders
{
    /**
     * Create the event handler.
     *
     * @param  ReminderRepositoryInterface $reminders
     * @return void
     */
    public function __construct(ReminderRepositoryInterface   $reminders)
    {
        $this->reminders = $reminders;
    }

    /**
     * Handle the event.
     *
     * @param  UserProfileWasMadeOffline  $event
     * @return void
     */
    public function handle(UserProfileWasMadeOffline $event)
    {
        $profile = $event->profile;
        $tutor = $profile->tutor;

        if ($profile->admin_status == UserProfile::OK) {
            
            $dates = $this->getReminderDates($profile);

            foreach ($dates as $date)
            {
                $reminder = Reminder::make(Reminder::GO_ONLINE, $date);
                $this->reminders->saveForUser($tutor, $reminder);
            }
        }

    }

    protected function getReminderDates()
    {
        $now      = Carbon::now();
        $offlineReminderPeriods = config('tutors.offlineReminderPeriods');
        $remindAt = [];

        foreach ($offlineReminderPeriods as $period)
        {
            $remindAt[] = $now->copy()->addDays($period);
        }
        return $remindAt;
    }
}

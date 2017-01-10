<?php

namespace App\Handlers\Events;

use App\Events\UserProfileWasMadeLive;
use App\Reminder;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveGoOnlineReminders
{
    protected $reminders;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(ReminderRepositoryInterface $reminders)
    {
        $this->reminders = $reminders;
    }

    /**
     * Handle the event.
     *
     * @param  UserProfileWasMadeLive  $event
     * @return void
     */
    public function handle(UserProfileWasMadeLive $event)
    {
        $profile = $event->profile;
        $tutor = $profile->tutor;

        foreach ($tutor->reminders as $reminder) {
            if ($reminder->name === Reminder::GO_ONLINE) {
                $this->reminders->delete($reminder);
            }
        }
    }
}

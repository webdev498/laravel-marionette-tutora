<?php namespace App\Handlers\Events\Jobs;

use App\Events\Jobs\JobWasMadeLive;
use App\Handlers\Events\EventHandler;
use App\Job;
use App\Reminder;
use App\Repositories\Contracts\ReminderRepositoryInterface;

class CreateCheckJobApplicantsReminder extends EventHandler
{

    /**
     * @var ReminderRepositoryInterface
     */
    protected $reminders;

    /**
     * Create the event handler.
     *
     * @param  ReminderRepositoryInterface   $reminders
     * @return void
     */
    public function __construct(
        ReminderRepositoryInterface   $reminders
    ) {
        $this->reminders = $reminders;
    }

    /**
     * Handle the event.
     *
     * @param  JobWasMadeLive $event
     * @return void
     */
    public function handle(JobWasMadeLive $event)
    {
        
        // Lookups
        $job        = $event->job;

        // Create Reminder
        $remindAt = $job->opened_at->copy()->addDays(config('tasks.students.check_job_applicants_period'));
        $reminder = Reminder::make(Reminder::JOB_MADE_LIVE, $remindAt);
        $job->reminders()->save($reminder);
        
    }
}

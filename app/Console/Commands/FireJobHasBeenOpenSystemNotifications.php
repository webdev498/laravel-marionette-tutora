<?php

namespace App\Console\Commands;

use App\Job;
use App\LessonBooking;
use App\LessonBookingReminder;
use App\Mailers\UserMailer;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use App\Student;
use App\Students\StudentStatusCalculator;
use App\Task;
use App\Tasks\StudentTasksTrait;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;

class FireJobHasBeenOpenSystemNotifications extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:fire_job_has_been_open_system_notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run code once jobs have been open for a period.';

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var ReminderRepositoryInterface
     */
    protected $reminders;


    /**
     * Create a new command instance.
     *
     * @param  DatabaseManager             $database
     * @param  ReminderRepositoryInterface $reminders

     * @return void
     */
    public function __construct(
        DatabaseManager              $database,
        ReminderRepositoryInterface  $reminders
    ) {
        parent::__construct();

        $this->database  = $database;
        $this->reminders = $reminders;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        loginfo("[ Background ] {$this->name}");

        $this->database->transaction(function () {
            $date = $this->getDate();

            $reminders = $this->reminders
                ->with(['remindable'])
                ->getJobMadeLiveByDate($date);


            foreach ($reminders as $reminder) {

                // Lookups
                $job = $reminder->remindable;

                if (! $job instanceof Job ) {
                    return;
                }
                $applicants = $job->tutors;

                // if the job has no applicant, then we will trigger an email at this point. For now, just delete the reminder.
                if ($applicants->count() == 0) {
                    $this->reminders->delete($reminder);
                }

                // If the job has had applicants, and they have replied, delete the reminder

                if ($applicants->count() > 0 && $job->repliedToApplicant()) {
                    $this->reminders->delete($reminder);
                }

                // if the job has an applicant, and they haven't replied, check to see when the last applicant was. If it was > 1 day ago, create a task.
                if ($applicants->count() > 0 && ! $job->repliedToApplicant()) {
                    $lines = $job->messageLines;


                    foreach ($lines as $line)
                    {

                        if ($line->created_at > Carbon::now()->subDays(1) && $applicants->count() < 6) {
                            
                            // Reply less than 1 day. So push back firing of event.
                            $reminder->remind_at = Carbon::now()->addDays(1);
                            $reminder->save();

                            return;

                        }
                    }

                    // Have replies more than 1 day - so if mismatched, create task. If not mismatched, then all is fine
                    $student = $job->user;

                    $calc = new StudentStatusCalculator($student);
                    if ($calc->isMismatched() && ! $calc->isNotReplied()) {
                      
                        $student->updateTasksForMismatchedHasJob();
                    }
                    $this->reminders->delete($reminder);

                }
            
            
            }
        });
    }

    protected function getDate()
    {
        return Carbon::now();
    }

}

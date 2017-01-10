<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\LessonBooking;
use App\Mailers\UserMailer;
use App\LessonBookingReminder;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\ReminderRepositoryInterface;

class EmailReviewLessonReminders extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:email_review_lesson_reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email review lesson reminders.';

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var ReminderRepositoryInterface
     */
    protected $reminders;

    /**
     * @var UserMailer
     */
    protected $mailer;

    /**
     * Create a new command instance.
     *
     * @param  DatabaseManager             $database
     * @param  ReminderRepositoryInterface $reminders
     * @param  UserMailer                  $mailer
     * @return void
     */
    public function __construct(
        DatabaseManager              $database,
        ReminderRepositoryInterface  $reminders,
        UserMailer                   $mailer
    ) {
        parent::__construct();

        $this->database  = $database;
        $this->reminders = $reminders;
        $this->mailer    = $mailer;
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
                ->getReviewLessonBookingByDate($date);

            foreach ($reminders as $reminder) {
                $booking = $reminder->remindable;

                if ($booking instanceof LessonBooking) {
                    $lesson       = $booking->lesson;
                    $relationship = $lesson->relationship;
                    $student      = $relationship->student;
                    $tutor        = $relationship->tutor;

                    $this->mailer->review($student, $tutor);

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

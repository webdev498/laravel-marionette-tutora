<?php namespace App\Console\Commands;

use Carbon\Carbon;
use App\LessonBooking;
use App\LessonBookingReminder;
use App\Mailers\StudentsLessonMailer;
use App\Twilio\StudentTwilio;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\ReminderRepositoryInterface;

class SendPendingLessonReminders extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:send_pending_lesson_reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email and text pending lesson reminders.';

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var ReminderRepositoryInterface
     */
    protected $reminders;

    /**
     * @var StudentsLessonMailer
     */
    protected $mailer;

    /**
     * @var StudentTwilio
     */
    protected $twilio;

    /**
     * Create a new command instance.
     *
     * @param  DatabaseManager             $database
     * @param  ReminderRepositoryInterface $reminders
     * @param  StudentsLessonMailer        $mailer
     * @param  StudentTwilio        $twilio     
     * @return void
     */
    public function __construct(
        DatabaseManager              $database,
        ReminderRepositoryInterface  $reminders,
        StudentsLessonMailer         $mailer,
        StudentTwilio                $twilio
    ) {
        parent::__construct();

        $this->database  = $database;
        $this->reminders = $reminders;
        $this->mailer    = $mailer;
        $this->twilio    = $twilio;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        loginfo("[ Background ] {$this->name}");

        $date = $this->getDate();

        $reminders = $this->reminders
            ->with(['remindable'])
            ->getStillPendingLessonBookingByDate($date);

        foreach ($reminders as $reminder) {
            $this->database->transaction(function () use($reminder) {
                $booking = $reminder->remindable;

                if ($booking instanceof LessonBooking) {
                    $lesson       = $booking->lesson;
                    $relationship = $lesson->relationship;
                    $student      = $relationship->student;

                    $this->mailer->lessonStillPending($student, $relationship, $lesson, $booking);
                    $this->twilio->lessonStillPending($student, $relationship, $lesson, $booking);

                }
                $this->reminders->delete($reminder);
            });
        }
    }

    protected function getDate()
    {
        return Carbon::now();
    }

}

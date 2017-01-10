<?php namespace App\Console\Commands;

use Carbon\Carbon;
use App\LessonBooking;
use App\LessonBookingReminder;
use App\Mailers\StudentsLessonMailer;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\ReminderRepositoryInterface;

class EmailUpcomingLessonReminders extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:email_upcoming_lesson_reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email reminders for upcoming lessons.';

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
     * Create a new command instance.
     *
     * @param  DatabaseManager             $database
     * @param  ReminderRepositoryInterface $reminders
     * @param  StudentsLessonMailer        $mailer
     * @return void
     */
    public function __construct(
        DatabaseManager              $database,
        ReminderRepositoryInterface  $reminders,
        StudentsLessonMailer         $mailer
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

        $date = $this->getDate();

        $reminders = $this->reminders
            ->with(['remindable'])
            ->getUpcomingLessonBookingByDate($date);

        foreach ($reminders as $reminder) {
            $this->database->transaction(function () use ($reminder) {
                $booking = $reminder->remindable;

                if ($booking instanceof LessonBooking && $booking->isConfirmed()) {
                    $lesson       = $booking->lesson;
                    $relationship = $lesson->relationship;
                    $student      = $relationship->student;

                    $this->mailer->lessonUpcoming($student, $relationship, $lesson, $booking);
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

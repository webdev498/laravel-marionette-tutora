<?php 

namespace App\Console\Commands;

use App\Mailers\TutorMailer;
use App\Mailers\StudentMailer;
use App\MessageLine;
use App\Reminder;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use App\Twilio\TutorTwilio;
use App\Twilio\StudentTwilio;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use App\Events\TutorNotReplied;
use App\Events\StudentNotReplied;

class SendMessageLineWrittenReminders extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:send_message_line_reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to tutors and students';

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var ReminderRepositoryInterface
     */
    protected $reminders;

    /**
     * @var TutorMailer
     */
    protected $tutorMailer;
	

	/**
     * @var TutorTwilio
     */
	protected $tutorTwilio;

    /**
     * @var StudentMailer
     */
    protected $studentMailer;
    

    /**
     * @var StudentTwilio
     */
    protected $studentTwilio;

	/**
     * @var MessageRepositoryInterface
     */
	protected $message;

    /**
     * Create a new command instance.
     *
     * @param  DatabaseManager             $database
     * @param  ReminderRepositoryInterface $reminders
     * @param  TutorMailer                 $tutorMailer
     * @param  TutorTwilio                 $tutorTwilio
     * @param  StudentMailer               $studentMailer
     * @param  StudentTwilio               $studentTwilio
     * @return void
     */
    public function __construct(
        DatabaseManager              $database,
        ReminderRepositoryInterface  $reminders,
        TutorMailer            		 $tutorMailer,
		TutorTwilio 				 $tutorTwilio,
        StudentMailer                $studentMailer,
        StudentTwilio                $studentTwilio,
		MessageRepositoryInterface   $message
    ) {
        parent::__construct();

        $this->database  = $database;
        $this->reminders = $reminders;
        $this->tutorMailer    = $tutorMailer;
		$this->tutorTwilio    = $tutorTwilio;
        $this->studentMailer    = $studentMailer;
        $this->studentTwilio    = $studentTwilio;
		$this->message   = $message;
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
            ->getNoReplyMessageLinesByDate($date);

        foreach ($reminders as $reminder) {
            $this->database->transaction(function () use ($reminder) {
                
                $line = $reminder->remindable;
                
                if ($line && ! $line->hasReply() ) {                    
                    $this->sendReminder($line, $reminder);    
                } 

                $this->reminders->delete($reminder);
            });
        }
    }

    protected function sendReminder(MessageLine $line, Reminder $reminder)
    {
                    
    	$message      = $line->message;
        $relationship = $message->relationship;
        $tutor 		  = $relationship->tutor;
        $student      = $relationship->student;

        

        switch ($reminder->name) {

            case Reminder::FIRSTNOREPLY:

                loginfo($tutor->id);
                $this->tutorMailer->noReplyToMessageLineFirstReminder($line, $tutor, $student, $relationship);
                $this->tutorTwilio->noReplyToMessageLineFirstReminder($line, $tutor, $student, $relationship);
                break;

            case Reminder::SECONDNOREPLY:
                event(new TutorNotReplied($tutor, $line));
                break;

            case Reminder::FIRSTNOREPLY_STUDENT:
                $this->studentMailer->noReplyToMessageLineFirstReminder($line, $tutor, $student, $relationship);
                $this->studentTwilio->noReplyToMessageLineFirstReminder($line, $tutor, $student, $relationship);
                break;

            case Reminder::SECONDNOREPLY_STUDENT:
                event(new StudentNotReplied($student, $line));
                break;
        }

    }

    protected function getDate()
    {
        return Carbon::now();
    }


}


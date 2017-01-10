<?php namespace App\Console\Commands;

use Carbon\Carbon;
use App\Tutor;
use App\UserProfile;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use App\Mailers\TutorMailer;


class SendGoOnlineReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tutora:send_go_online_reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to go back online after going offline';

    /**
     * @var LessonBookings
     */
    protected $bookings;

    /**
     * @var Reminders
     */
    protected $reminders;

    /**
     * @var TutorsLessonMailer
     */
    protected $TutorMailer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ReminderRepositoryInterface         $reminders,
        TutorMailer                         $tutorMailer
    ) {
        parent::__construct();
        $this->reminders = $reminders;
        $this->tutorMailer = $tutorMailer;
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
            ->getGoOnlineByDate($date);

        foreach ($reminders as $reminder)
        {
            $tutor = $reminder->remindable;
            
            if ($tutor instanceof Tutor) {
                $profile = $tutor->profile;
           
                if ($profile instanceof UserProfile && $profile->status == UserProfile::OFFLINE && $profile->admin_status == UserProfile::OK) {
        
                        $this->tutorMailer->goOnlineReminder($tutor);

                }
            $this->reminders->delete($reminder);
            }
        }
            
    }


    protected function getDate()
    {
        return Carbon::now();
    }
}

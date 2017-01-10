<?php

namespace App\Console\Commands;

use App\Repositories\Contracts\NotificationScheduleRepositoryInterface;
use App\Tutor;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;

class SendNotifications extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:send_notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications to tutors and students.';

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var NotificationScheduleRepositoryInterface
     */
    protected $schedules;

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
        NotificationScheduleRepositoryInterface   $schedules
    ) {
        parent::__construct();

        $this->database  = $database;
        $this->schedules = $schedules;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        loginfo("[ Background ] {$this->name}");

        
        $date = $this->getNow();

        $schedulesQuery = $this->schedules->queryBySendAt($date);
        
        $schedulesQuery->chunk(200, function ($schedules) {
            foreach ($schedules as $schedule) {
            
                $notification = $schedule->getNext();

                $notification->action($schedule);
            }
        });   
    }

    protected function getNow()
    {
        return Carbon::now();
    }

}

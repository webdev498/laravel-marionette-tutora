<?php

use App\Schedules\TutorJobsSchedule;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsForTutors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tutors = \App\Tutor::whereHas('schedules', function ($query) {
            $query->where('type', '=', 'TutorJobs');
        }, '=', 0)->get();

        $send_at = Carbon::now()->addDays(1)->hour(config('jobs.send_at_hour'));

        foreach ($tutors as $tutor)
        {
            DB::insert('
                insert into notification_schedules
                    (user_id, type, send_at, last_notification_name, created_at, updated_at)
                values (?, ?, ?, ?, NOW(), NOW());
            ', [
                $tutor->id,
                'TutorJobs',
                $send_at,
                'DefaultJobsNotification'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        TutorJobsSchedule::whereIn("type", ["TutorJobs"])
        ->delete();
    }
}

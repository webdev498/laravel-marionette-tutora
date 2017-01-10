<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class AddStudentMarketingToSubscriptionSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $students = DB::select("SELECT users.id from users 
            JOIN role_user ON role_user.user_id = users.id
            JOIN roles ON roles.id = role_user.role_id
            WHERE roles.name = 'student' ");

        foreach ($students as $student)
        {
            DB::insert('
                insert into notification_schedules
                    (user_id, type, send_at, last_sent_at, last_notification_name, count, created_at, updated_at)
                values (?, ?, ?, ?, ?, ?, NOW(), NOW());
            ', [
                $student->id,
                'StudentMarketing',
                day_from_date(Carbon::now(), 'tuesday', 'am'),
                null,   // last sent at
                'DefaultMarketingNotification', // Last notification
                0     // Count
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
        //
    }
}

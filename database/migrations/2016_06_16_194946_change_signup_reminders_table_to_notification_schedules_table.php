<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSignupRemindersTableToNotificationSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('signup_reminders', 'notification_schedules');

        Schema::table('notification_schedules', function (Blueprint $table) {
            $table->renameColumn('remind_at', 'send_at');
            $table->renameColumn('last_reminded_at', 'last_sent_at');
            $table->renameColumn('reminder_count', 'count');
            $table->renameColumn('last_reminder_type', 'last_notification_name');
            $table->string('type')->after('user_id')->nullable();
        });

        DB::update("
            update notification_schedules
            set `type` = 'TutorSignup'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_schedules', function (Blueprint $table) {
            $table->renameColumn('send_at', 'remind_at');
            $table->renameColumn('last_sent_at', 'last_reminded_at');
            $table->renameColumn('count', 'reminder_count');
            $table->renameColumn('last_notification_name', 'last_reminder_type');
            $table->dropColumn('type');
        });

        Schema::rename('notification_schedules', 'signup_reminders');
    }
}

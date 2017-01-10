<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNotificationSchedulesTableForTutorSignups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update("
            UPDATE notification_schedules
            SET `last_notification_name` = 'DefaultSignupNotification'
            WHERE `last_notification_name` = 'DefaultReminder'
        ");
        DB::update("
            UPDATE notification_schedules
            SET `last_notification_name` = 'FirstSignupNotification'
            WHERE `last_notification_name` = 'FirstReminder'
        ");
        DB::update("
            UPDATE notification_schedules
            SET `last_notification_name` = 'SecondSignupNotification'
            WHERE `last_notification_name` = 'SecondReminder'
        ");
        DB::update("
            UPDATE notification_schedules
            SET `last_notification_name` = 'ThirdSignupNotification'
            WHERE `last_notification_name` = 'ThirdReminder'
        ");
        DB::update("
            UPDATE notification_schedules
            SET `last_notification_name` = 'FinalSignupNotification'
            WHERE `last_notification_name` = 'FinalReminder'
        ");
        DB::update("
            UPDATE notification_schedules
            SET `last_notification_name` = 'FirstIdentificationNotification'
            WHERE `last_notification_name` = 'FirstIdentificationReminder'
        ");
        DB::update("
            UPDATE notification_schedules
            SET `last_notification_name` = 'SecondIdentificationNotification'
            WHERE `last_notification_name` = 'SecondIdentificationReminder'
        ");
        DB::update("
            UPDATE notification_schedules
            SET `last_notification_name` = 'ThirdIdentificationNotification'
            WHERE `last_notification_name` = 'ThirdIdentificationReminder'
        ");
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

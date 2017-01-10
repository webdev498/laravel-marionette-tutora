<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLessonBookingsTableMarkOldLessonsAsTransferred extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update("UPDATE lesson_bookings 
            SET transfer_status = 'transferred'
            WHERE status = 'completed'
            AND charge_status = 'paid'
            AND transfer_status = 'pending'
            AND start_at < '2016-04-18' ");
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_bookings', function (Blueprint $table) {
            //
        });
    }
}

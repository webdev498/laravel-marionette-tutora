<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonBookingRemindersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_booking_reminders', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lesson_booking_id')->index();
            $table->string('name');
            $table->timestamps();

            $table->foreign('lesson_booking_id')->references('id')->on('lesson_bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lesson_booking_reminders');
    }

}

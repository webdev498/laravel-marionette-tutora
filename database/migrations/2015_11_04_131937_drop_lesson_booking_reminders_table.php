<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropLessonBookingRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('lesson_booking_reminders');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('lesson_booking_reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lesson_booking_id')->index();
            $table->string('name');
            $table->timestamps();

            $table->foreign('lesson_booking_id')->references('id')->on('lesson_bookings')->onDelete('cascade');
        });

        DB::insert("
            insert into lesson_booking_reminders
                (lesson_booking_id, name, created_at, updated_at)
            select remindable_id, name, created_at, NOW()
            from reminders
        ");
    }
}

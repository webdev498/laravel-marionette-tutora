<?php

use Carbon\Carbon;
use App\LessonBooking;
use Illuminate\Database\Schema\Blueprint;
Use Illuminate\Database\Migrations\Migration;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('remindable_id')->index();
            $table->string('remindable_type');
            $table->string('name');
            $table->timestamp('remind_at');
            $table->timestamps();
        });

        $reminders = DB::select('select * from lesson_booking_reminders;');

        foreach ($reminders as $reminder) {
            switch ($reminder->name) {
                case 'upcoming':
                    $minutes = config('lessons.reminder_upcoming_period', 1440);
                    break;
                case 'still_pending':
                    $minutes = config('lessons.reminder_still_pending_period', 2880);
                    break;
                case 'review':
                    $minutes = config('lessons.reminder_review_period', -1440);
                    break;
            }

            $date = (new Carbon($reminder->created_at))->addMinutes($minutes);

            DB::insert('
                insert into reminders
                    (remindable_id, remindable_type, name, remind_at, created_at, updated_at)
                values (?, ?, ?, ?, ?, NOW());
            ', [
                $reminder->id,
                LessonBooking::class,
                $reminder->name,
                $date->toDateTimeString(),
                $reminder->created_at
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
        Schema::drop('reminders');
    }
}

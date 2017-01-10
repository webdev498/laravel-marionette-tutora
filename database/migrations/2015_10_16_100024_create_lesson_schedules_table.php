<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonSchedulesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_schedules', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lesson_id')->index();
            $table->tinyInteger('minute');
            $table->tinyInteger('hour');
            $table->tinyInteger('day_of_the_month');
            $table->tinyInteger('month');
            $table->tinyInteger('day_of_the_week');
            $table->tinyInteger('nth')->unsigned()->default(1);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('last_scheduled_at')->nullable();
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lesson_schedules');
    }

}

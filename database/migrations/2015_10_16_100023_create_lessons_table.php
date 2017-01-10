<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tutor_id')->index();
            $table->unsignedInteger('student_id')->index();
            $table->unsignedInteger('subject_id')->index();
            $table->smallInteger('duration')->unsigned();
            $table->smallInteger('rate')->unsigned();
            $table->string('location');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lessons');
    }

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTutorTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_tutor', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->boolean('favourite')->default(false);
            $table->boolean('applied')->default(false);
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('tuition_jobs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('job_tutor');
    }
}

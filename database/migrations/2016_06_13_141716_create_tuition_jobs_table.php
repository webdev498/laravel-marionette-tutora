<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTuitionJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tuition_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->index()->unique();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('subject_id')->unsigned()->index();
            $table->smallInteger('status')->unsigned();
            $table->text('message');
            $table->text('closed_for');
            $table->dateTime('opened_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tuition_jobs');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeJobRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_tutor', function (Blueprint $table) {
            $table->dropColumn('message_line_id');
        });

        Schema::drop('relationship_tuition_jobs');

        Schema::create('tuition_job_message_line', function (Blueprint $table) {

            $table->increments('id');

            // relationship id
            $table->integer('message_line_id')->unsigned()->index();
            $table->foreign('message_line_id')
                ->references('id')->on('message_lines')
                ->onDelete('cascade');

            // job id
            $table->integer('job_id')->unsigned()->index();
            $table->foreign('job_id')
                ->references('id')->on('tuition_jobs')
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
        Schema::table('job_tutor', function (Blueprint $table) {
            $table->unsignedInteger('message_line_id')->after('user_id')->nullable();
        });

        Schema::create('relationship_tuition_jobs', function (Blueprint $table) {

            $table->increments('id');

            // relationship id
            $table->integer('relationship_id')->unsigned()->index();
            $table->foreign('relationship_id')
                ->references('id')->on('relationships')
                ->onDelete('cascade');

            // job id
            $table->integer('job_id')->unsigned()->index();
            $table->foreign('job_id')
                ->references('id')->on('tuition_jobs')
                ->onDelete('cascade');
        });

        Schema::drop('tuition_job_message_line');
    }
}

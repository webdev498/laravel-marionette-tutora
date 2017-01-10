<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTuitionJobApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('job_tutor', 'tuition_job_tutor');
        Schema::rename('tuition_job_message_line', 'tuition_job_applications');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('tuition_job_tutor', 'job_tutor');
        Schema::rename('tuition_job_applications', 'tuition_job_message_line');
    }
}

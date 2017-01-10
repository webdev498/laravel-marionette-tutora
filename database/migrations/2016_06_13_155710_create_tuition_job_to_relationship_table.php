<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTuitionJobToRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('relationship_tuition_jobs');
    }
}

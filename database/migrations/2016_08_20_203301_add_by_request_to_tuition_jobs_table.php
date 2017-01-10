<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddByRequestToTuitionJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tuition_jobs', function (Blueprint $table) {
            $table->tinyInteger('by_request')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tuition_jobs', function (Blueprint $table) {
            $table->dropColumn('by_request');
        });
    }
}

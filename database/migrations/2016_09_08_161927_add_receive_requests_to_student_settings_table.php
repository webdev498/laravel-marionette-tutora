<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceiveRequestsToStudentSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_settings', function (Blueprint $table) {
            $table->smallInteger('receive_requests')->after('user_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_settings', function (Blueprint $table) {
            $table->dropColumn('receive_requests');
        });
    }
}

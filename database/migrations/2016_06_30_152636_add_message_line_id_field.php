<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageLineIdField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add
        Schema::table('job_tutor', function (Blueprint $table) {
            $table->unsignedInteger('message_line_id')->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // add
        Schema::table('job_tutor', function (Blueprint $table) {
            $table->dropColumn('message_line_id');
        });
    }
}

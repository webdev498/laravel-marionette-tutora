<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonFieldToBackgroundChecks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_background_checks', function ($table) {
            $table->smallInteger('rejected_for')->unsigned()->after('admin_status')->nullable();
            $table->text('reject_comment')->after('rejected_for')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_background_checks', function ($table) {
            $table->dropColumn('rejected_for');
            $table->dropColumn('reject_comment');
        });
    }
}

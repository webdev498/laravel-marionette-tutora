<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransferStatusToLessonBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_bookings', function (Blueprint $table) {
            $table->string('transfer_status')->default('pending')->after('charge_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_bookings', function (Blueprint $table) {
            $table->dropColumn('transfer_status');
        });
    }
}

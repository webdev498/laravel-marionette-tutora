<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransferDateToLessonBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_bookings', function (Blueprint $table) {
            $table->timestamp('transferred_at')->after('transfer_status')->nullable();
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
            $table->dropColumn('transferred_at');
        });
    }
}

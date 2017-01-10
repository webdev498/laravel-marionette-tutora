<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentAttemptsAndLastAttemptedAtColumnsToLessonBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_bookings', function (Blueprint $table) {
            $table->smallInteger('payment_attempts')->after('charge_status')->default(0);
            $table->timestamp('last_attempted_at')->after('payment_attempts')->nullable();
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
            $table->dropColumn('payment_attempts');
            $table->dropColumn('last_attempted_at');
        });
    }
}

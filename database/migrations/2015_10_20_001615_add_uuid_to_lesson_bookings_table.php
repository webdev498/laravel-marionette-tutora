<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuidToLessonBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add
        Schema::table('lesson_bookings', function (Blueprint $table) {
            $table->string('uuid', 36)->after('id');
        });

        // update
        DB::update('update lesson_bookings set uuid = id;');

        // index & unique
        Schema::table('lesson_bookings', function (Blueprint $table) {
            $table->index('uuid');
            $table->unique('uuid');
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
            $table->dropColumn('uuid');
        });
    }
}

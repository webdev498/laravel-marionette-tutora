<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonBookingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_bookings', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lesson_id')->index();
            $table->timestamp('start_at');
            $table->timestamp('finish_at');
            $table->smallInteger('duration')->unsigned();
            $table->smallInteger('rate')->unsigned();
            $table->decimal('price', 15, 2)->unsigned();
            $table->string('location');
            $table->string('status')->default('pending');
            $table->string('charge_status')->default('pending');
            $table->string('charge_id')->nullable();
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lesson_bookings');
    }

}

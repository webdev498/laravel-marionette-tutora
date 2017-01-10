<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taskables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('task_id')->index();
            $table->unsignedInteger('taskable_id')->index();
            $table->string('taskable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('taskables');
    }
}

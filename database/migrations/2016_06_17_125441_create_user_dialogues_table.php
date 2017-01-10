<?php

use App\Dialogue\UserDialogue;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUserDialoguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_dialogues', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->enum('type', ['basic', 'custom']);
            $table->string('route', 200);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_dialogues');
    }
}

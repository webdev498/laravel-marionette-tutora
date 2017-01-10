<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUserDialogueInteractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_dialogue_interactions', function (Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unsigned()->index();
            $table->unsignedInteger('user_dialogue_id')->unsigned()->index();
            $table->string('data', 200)->nullable();
            $table->integer('duration')->unsigned()->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('user_dialogue_id')
                ->references('id')->on('user_dialogues')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_dialogue_interactions');
    }
}

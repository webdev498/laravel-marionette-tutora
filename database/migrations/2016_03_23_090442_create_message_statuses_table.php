<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('message_id');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('unread');
            $table->tinyInteger('archived');
            $table->timestamps();

            $table->foreign('message_id')
                  ->references('id')->on('messages')
                  ->onDelete('cascade');
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });

        $messages = \App\Message::with(
                'relationship', 
                'relationship.tutor', 
                'relationship.student')
            ->where('id','>=', 0)->get();

        foreach ($messages as $message)
        {
            $statusTutor = \App\MessageStatus::make($message, $message->relationship->tutor);
            $statusStudent = \App\MessageStatus::make($message, $message->relationship->student);
            $statusTutor->save();
            $statusStudent->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('message_statuses');
    }
}

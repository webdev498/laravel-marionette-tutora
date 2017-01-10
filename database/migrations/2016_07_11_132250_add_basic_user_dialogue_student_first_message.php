<?php

use App\Dialogue\UserDialogue;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddBasicUserDialogueStudentFirstMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         UserDialogue::create([
            "name" => "student_first_message",
            "type" => UserDialogue::BASIC,
            "route" => "/student/messages/:id/student_first_message"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        UserDialogue::whereIn("name", ["student_first_message"])
        ->delete();
    }
}

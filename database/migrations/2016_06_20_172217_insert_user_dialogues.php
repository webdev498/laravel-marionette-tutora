<?php

use App\Dialogue\UserDialogue;
use Illuminate\Database\Migrations\Migration;

class InsertUserDialogues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        UserDialogue::create([
            "name" => "welcome",
            "type" => UserDialogue::CUSTOM,
            "route" => "/tutors/:uuid/welcome"]);

        UserDialogue::create([
            "name" => "quiz_intro",
            "type" => UserDialogue::CUSTOM,
            "route" => "/tutors/:uuid/quiz_intro"]);
        UserDialogue::create([
            "name" => "quiz_prep",
            "type" => UserDialogue::CUSTOM,
            "route" => "/tutors/:uuid/quiz_prep/:tab"]);
        UserDialogue::create([
            "name" => "quiz_questions",
            "type" => UserDialogue::CUSTOM,
            "route" => "/tutors/:uuid/quiz_questions"]);

        UserDialogue::create([
            "name" => "review_notification",
            "type" => UserDialogue::BASIC,
            "route" => "/tutors/:uuid/review_notification"]);

        UserDialogue::create([
            "name" => "first_message",
            "type" => UserDialogue::BASIC,
            "route" => "/tutor/messages/:id/first_message"]);

        UserDialogue::create([
            "name" => "first_reply",
            "type" => UserDialogue::BASIC,
            "route" => "/student/messages/:id/first_reply"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        UserDialogue::whereIn("name", ["welcome", "quiz_intro", "quiz_prep", "quiz_questions", "review_notification", "first_message", "first_reply"])
        ->delete();
    }
}

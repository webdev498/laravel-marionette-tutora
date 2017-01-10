<?php

use App\Dialogue\UserDialogue;
use Illuminate\Database\Seeder;

class UserDialogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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

        UserDialogue::create([
            "name" => "student_first_message",
            "type" => UserDialogue::BASIC,
            "route" => "/student/messages/:id/student_first_message"]);

        UserDialogue::create([
            "name" => "student_job_created",
            "type" => UserDialogue::CUSTOM,
            "route" => "/student/dashboard/student_job_created"]);

        UserDialogue::create([
            "name" => "job_board_introduction",
            "type" => UserDialogue::BASIC,
            "route" => "/tutor/dashboard/job_board_introduction"]);

        UserDialogue::create([
            "name" => "book_trial_lesson",
            "type" => UserDialogue::BASIC,
            "route" => "/tutor/messages/:id/book_trial_lesson"]);
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Dialogue\UserDialogue;

class AddStudentJobCreatedDialogue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        UserDialogue::create([
            "name" => "student_job_created",
            "type" => UserDialogue::BASIC,
            "route" => "/student/dashboard/student_job_created"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        UserDialogue::whereIn("name", ["student_job_created"])
            ->delete();
    }
}

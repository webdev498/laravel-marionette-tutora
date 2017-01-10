<?php

use App\Dialogue\UserDialogue;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddBasicUserDialogueJobBoardIntroduction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        UserDialogue::create([
            "name" => "job_board_introduction",
            "type" => UserDialogue::BASIC,
            "route" => "/tutor/dashboard/job_board_introduction"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        UserDialogue::whereIn("name", ["job_board_introduction"])
        ->delete();
    }
}

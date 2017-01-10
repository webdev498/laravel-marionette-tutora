<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertTutorWelcomedIntoUserDialogueInteractions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tutors = \App\Tutor::all();
        
        foreach ($tutors as $tutor)
        {
            DB::insert('
                insert into user_dialogue_interactions
                    (user_id, user_dialogue_id, data, duration, created_at, updated_at)
                values (?, ?, ?, ?, NOW(), NOW());
            ', [
                $tutor->id,
                1,  // welcome dialogue
                NULL,
                NULL
            ]);
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_dialogue_interactions', function (Blueprint $table) {
            //
        });
    }
}

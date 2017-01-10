<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobApplicationToRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relationships', function (Blueprint $table) {
            $table->smallInteger('is_application')->after('is_confirmed')->default(0);
        });

        // Update relationship table
        $relationships = \App\Relationship::where('created_at', '>', '2016-08-01')->get();

        foreach ($relationships as $relationship)
        {
            $tutor = $relationship->tutor;
            $message = $relationship->message;
            $line = $message->lines->first();

            if ($line && $line->user_id == $tutor->id) {
                $relationship->is_application = true;
                $relationship->save();

            } 
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relationships', function (Blueprint $table) {
            $table->dropColumn('is_application');
        });
    }
}

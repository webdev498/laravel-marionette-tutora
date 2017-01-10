<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRelationshipStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update("
            UPDATE relationships SET is_confirmed = 1
            WHERE relationships.status = 'confirmed'
        ");
        DB::update("
            UPDATE relationships SET is_confirmed = 0
            WHERE relationships.status = 'pending'
        ");
        DB::update("
            UPDATE relationships SET status = 'pending'
            WHERE relationships.status = 'chatting'
        ");
        DB::update("
            UPDATE relationships SET status = 'mismatched'
            WHERE relationships.reason IS NOT NULL
        ");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

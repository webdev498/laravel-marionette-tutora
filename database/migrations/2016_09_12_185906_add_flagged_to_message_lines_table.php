<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlaggedToMessageLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_lines', function (Blueprint $table) {
            $table->smallInteger('flagged')->after('body')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_lines', function (Blueprint $table) {
            $table->dropColumn('flagged');
        });
    }
}

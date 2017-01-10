<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropWelcomedAndNotifiedOfReviewColumnsFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('welcomed');
            $table->dropColumn('notified_of_review');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('welcomed')->unsigned()->default(false);
            $table->boolean('notified_of_review')->unsigned()->default(false);
        });
        DB::table('users')->where('welcomed', false)->update(['welcomed' => true]);
        DB::table('users')->where('notified_of_review', false)->update(['notified_of_review' => true]);
    }
}

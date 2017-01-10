<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsPendingToUserRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_requirements', function (Blueprint $table) {
            $table->boolean('is_pending')->unsigned()->default(1)->after('is_optional');
        });

        DB::update('update user_requirements set is_pending = 0 where is_completed = 1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_requirements', function (Blueprint $table) {
            $table->dropColumn('is_pending');
        });
    }
}

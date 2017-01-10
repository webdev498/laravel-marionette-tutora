<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForToUserRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_requirements', function (Blueprint $table) {
            $table->string('for')->nullable()->after('section');
        });

        DB::update("update `user_requirements` set `for` = 'profile_submit';");
        DB::update("update `user_requirements` set `for` = 'payouts' where `name` = 'payment_details';");
        DB::update("update `user_requirements` set `for` = 'other'  where `name` = 'dbs_check';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_requirements', function (Blueprint $table) {
            $table->dropColumn('for');
        });
    }
}

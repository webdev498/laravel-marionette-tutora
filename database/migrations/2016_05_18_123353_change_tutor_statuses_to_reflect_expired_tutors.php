<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTutorStatusesToReflectExpiredTutors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update(" UPDATE `user_profiles`
            SET `admin_status` = 'pending', status = 'expired'
            WHERE `user_profiles`.`admin_status` = 'rejected'
            AND `user_profiles`.`status` != 'live' ");

        DB::update(" UPDATE `user_profiles`
            SET `admin_status` = 'pending', status = 'expired'
            WHERE `user_profiles`.`admin_status` = 'rejectable' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}

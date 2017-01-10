<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequiredToUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('required')->nullable()->after('quality');
        });

        DB::update("update `user_profiles` set `required` = null;");

        DB::update("
            update `user_profiles`
            set `required` = 'payouts'
            where `user_profiles`.`user_id` IN (
                select distinct(`user_id`)
                from `user_requirements`
                left join `users`
                on `users`.`id` =`user_requirements`.`user_id`
                where `user_requirements`.`for` = 'payouts'
                and `user_requirements`.`is_completed` = 0
                and `user_requirements`.`is_optional` = 0
                and `users`.`last_four` is null
            );
        ");

        DB::update("
            update `user_profiles`
            set `required` = 'profile_submit'
            where `user_profiles`.`user_id` IN (
                select distinct(`user_id`)
                from `user_requirements`
                where `user_requirements`.`for` = 'profile_submit'
                and `user_requirements`.`is_completed` = 0
                and `user_requirements`.`is_optional` = 0
            );
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('required');
        });
    }
}

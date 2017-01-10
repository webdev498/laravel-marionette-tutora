<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusAndAdminStatusToUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('status')->after('user_id');
            $table->string('admin_status')->after('status');
        });
        // whitewash
        DB::update("update `user_profiles` set `status` = 'new', `admin_status` = 'pending';");
        // status
        DB::update("
            update `user_profiles`
            set `status` = 'submittable'
            where `user_profiles`.`user_id` NOT IN (
                select distinct(`user_id`)
                from `user_requirements`
                where `user_requirements`.`for` = 'profile_submit'
                and `user_requirements`.`is_completed` = 0
                and `user_requirements`.`is_optional` = 0
            );
        ");
        DB::update("update `user_profiles` set `status` = 'pending' where `live` = 1 and `rejected` = 0 and `reviewed` = 0;");
        DB::update("update `user_profiles` set `status` = 'live'    where `live` = 1 and `rejected` = 0 and `reviewed` = 1;");
        DB::update("update `user_profiles` set `status` = 'offline' where `live` = 0 and `rejected` = 0 and `reviewed` = 1;");
        // admin_status
        DB::update("update `user_profiles` set `admin_status` = 'review'   where `live` = 1 and `rejected` = 0 and `reviewed` = 0;");
        DB::update("update `user_profiles` set `admin_status` = 'ok'       where `rejected` = 0 and `reviewed` = 1;");
        DB::update("update `user_profiles` set `admin_status` = 'rejected' where `rejected` = 1;");
        // Drop
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('live', 'reviewed', 'rejected');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->boolean('reviewed')->unsigned()->default(0)->after('user_id');
            $table->boolean('rejected')->unsigned()->default(0)->after('reviewed');
            $table->boolean('live')->unsigned()->default(0)->after('rejected');
        });
        // Update
        DB::update("update `user_profiles` set `live` = 1 where `status` = 'live';");
        DB::update("update `user_profiles` set `rejected` = 1 where `admin_status` = 'rejected';");
        DB::update("update `user_profiles` set `reviewed` = 1 where `admin_status` = 'ok';");
        // Drop
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('status', 'admin_status');
        });
    }

}

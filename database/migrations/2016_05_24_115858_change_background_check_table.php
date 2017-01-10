<?php

use App\UserBackgroundCheck;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBackgroundCheckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_background_checks', function($table)
        {
            $table->renameColumn('expires_on', 'issued_at');
            $table->char('uuid', 36)->after('id');
            $table->integer('image_id')->unsigned()->index()->after('user_id');
            $table->integer('admin_status')->unsigned()->after('dbs');
            $table->integer('type')->after('admin_status');
            $table->string('certificate_number')->after('type');
            $table->string('first_name')->after('certificate_number');
            $table->string('last_name')->after('first_name');
            $table->date('dob')->nullable()->after('last_name');
        });

        DB::update("update `user_requirements` set `name` = 'background_check'  where `name` = 'dbs_check';");

        DB::update("update `user_background_checks` set `type` = ".UserBackgroundCheck::TYPE_DBS_CHECK.";");
        DB::update("update `user_background_checks` set `admin_status` = ".UserBackgroundCheck::ADMIN_STATUS_APPROVED.";");

        // update
        DB::update('update `user_background_checks` set uuid = id;');

        // index & unique
        Schema::table('user_background_checks', function (Blueprint $table) {
            $table->index('uuid');
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_background_checks', function($table)
        {
            $table->renameColumn('issued_at', 'expires_on');
            $table->dropColumn('type');
            $table->dropColumn('image_id');
            $table->dropColumn('certificate_number');
            $table->dropColumn('admin_status');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('dob');
            $table->dropColumn('uuid');
        });

        DB::update("update `user_requirements` set `name` = 'dbs_check'  where `name` = 'background_check';");
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLegalNameToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('legal_first_name')->nullable()->after('last_name');
            $table->string('legal_last_name')->nullable()->after('legal_first_name');
        });

        DB::update('update users set legal_first_name = first_name, legal_last_name = last_name;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('legal_first_name', 'legal_last_name');
        });
    }
}

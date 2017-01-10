<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntentToRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_lines', function (Blueprint $table) {
            $table->dropColumn('intent');
            $table->dropColumn('reason');
        });

        Schema::table('relationships', function (Blueprint $table) {
            $table->string('reason', 255)->after('status')->nullable();
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
            $table->boolean('intent')->nullable()->default(0)->after('body');
            $table->string('reason', 255)->after('intent')->nullable();
        });

        Schema::table('relationships', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
    }
}

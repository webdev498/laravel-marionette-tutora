<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTasksColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->timestamp('action_at')->nullable()->default(null)->after('description');
            $table->renameColumn('description', 'body');
        });

        DB::update('update tasks set action_at = due_at');

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('due_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->date('due_at')->nullable()->default(null)->after('body');
            $table->renameColumn('body', 'description');
        });

        DB::update('update tasks set due_at = action_at');

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('action_at');
        });
    }
}

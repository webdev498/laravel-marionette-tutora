<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaskableColumnsToTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedInteger('taskable_id')->index()->after('id');
            $table->string('taskable_type')->after('taskable_id');
        });

        DB::update('update tasks set taskable_id = user_id, taskable_type = ?', ['App\\User']);

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_user_id_foreign');
            $table->dropIndex('tasks_user_id_index');
            $table->dropColumn('user_id');
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
            $table->unsignedInteger('user_id')->after('id');
        });

        DB::delete('delete from tasks where taskable_type != ?', ['App\\User']);
        DB::update('update tasks set user_id = taskable_id');

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_taskable_id_index');
            $table->dropColumn('taskable_id', 'taskable_type');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}

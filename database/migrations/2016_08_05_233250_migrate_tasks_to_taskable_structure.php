<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateTasksToTaskableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert('
            insert into taskables (
                task_id,
                taskable_id,
                taskable_type
            )
            select id, taskable_id, taskable_type
            from tasks
        ');

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('taskable_id');
            $table->dropColumn('taskable_type');
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
            $table->unsignedInteger('taskable_id')->index()->after('id');
            $table->string('taskable_type')->after('taskable_id');
        });

        DB::update('
            update tasks, taskables set
                tasks.id = taskables.task_id,
                tasks.taskable_type = taskables.taskable_type,
                tasks.taskable_id = taskables.taskable_id
                where tasks.id = taskables.task_id
        ');
    }
}

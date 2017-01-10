<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTutorTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Find all tasks with category 'first_lesson' and add "OLD"
        $tasks = \App\Task::where('category', '=', 'first_lesson')->get();

        foreach ($tasks as $task) {
            $task->body = 'OLD - ' . $task->body;
            $task->save();
        }

        // Find all tasks with category 'rebook' and add "OLD"
        $tasks = \App\Task::where('category', '=', 'rebook')->get();

        foreach ($tasks as $task) {
            $task->body = 'OLD - ' . $task->body;
            $task->save();
        }

        // Find all tasks with category 'rebook' and add "OLD"
        $tasks = \App\Task::where('category', '=', 'first_with_student')->get();

        foreach ($tasks as $task) {
            $task->body = 'OLD - ' . $task->body;
            $task->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

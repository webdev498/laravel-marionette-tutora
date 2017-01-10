<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Task;

class MergeNoReplyTasksIntoOneTaskType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tasks = Task::where('category', '=', 'not_replied_has_job')->get();

        foreach ($tasks as $task)
        {
            $task->category = 'not_replied';
            $task->save();
        }

        $tasks = Task::where('category', '=', 'not_replied_no_job')->get();

        foreach ($tasks as $task)
        {
            $task->category = 'not_replied';
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

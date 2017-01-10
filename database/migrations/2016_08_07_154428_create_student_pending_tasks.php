<?php

use App\Student;
use App\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;
class CreateStudentPendingTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        // Get all students who are currently at pending.
        $students = Student::where('status', 'pending')->get();

        // Assign a task if they don't have one, or update the current task.
        foreach ($students as $student)
        {
            $tasks = $student->tasks;

            if ($tasks->count() == 0) {
                $task = new Task();
                $task->body = 'PENDING';
                $task->category = Task::PENDING_LESSON;
                $task->action_at = Carbon::now();
                $student->tasks()->save($task); 
            } else {
                $task = $tasks->first();
                $task->category = Task::PENDING_LESSON;
                $task->save();
            }

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

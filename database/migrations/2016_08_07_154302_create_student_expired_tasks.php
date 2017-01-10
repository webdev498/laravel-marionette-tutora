<?php

use App\Student;
use App\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Carbon\Carbon;

class CreateStudentExpiredTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        // Get all students who are currently at expired.
        $students = Student::where('status', 'expired')->get();

        // Assign a task if they don't have one, or update the current task.
        foreach ($students as $student)
        {
            $tasks = $student->tasks;

            if ($tasks->count() == 0) {
                $task = new Task();
                $task->body = 'EXPIRED';
                $task->category = Task::EXPIRED_LESSON;
                $task->action_at = Carbon::now();
                $student->tasks()->save($task); 
            } else {
                $task = $tasks->first();
                $task->category = Task::EXPIRED_LESSON;
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

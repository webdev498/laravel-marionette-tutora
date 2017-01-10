<?php

use App\Student;
use App\Task;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFailedPaymentTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        // Get all students who are currently at failed.
        $students = Student::where('status', 'failed')->get();

        // Assign a task if they don't have one, or update the current task.
        foreach ($students as $student)
        {
            $tasks = $student->tasks;

            if ($tasks->count() == 0) {
                $task = new Task();
                $task->body = 'FAILED';
                $task->category = Task::FAILED_PAYMENT;
                $task->action_at = Carbon::now();
                $student->tasks()->save($task); 
            } else {
                $task = $tasks->first();
                $task->category = Task::FAILED_PAYMENT;
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

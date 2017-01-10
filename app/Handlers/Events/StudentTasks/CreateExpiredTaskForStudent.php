<?php namespace App\Handlers\Events\StudentTasks;

use App\Events\LessonBookingHasExpired;
use App\Handlers\Events\EventHandler;
use App\Student;
use App\Task;
use Carbon\Carbon;

class CreateExpiredTaskForStudent extends EventHandler
{

    /**
     * Create the event handler.
     *
     * @param  StudentsLessonMailer $mailer
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingHasExpired $event
     * @return void
     */
    public function handle(LessonBookingHasExpired $event)
    {
        $booking      = $event->booking;
        $lesson       = $booking->lesson;
        $relationship = $lesson->relationship;
        $student      = $relationship->student;

        $tasks = $student->tasks()->where('category', Task::EXPIRED_LESSON)->count();

        if ($tasks) return;
        
        // Create Task 
        $task = new Task();
        
        $task->body      = 'EXPIRED LESSON';
        $task->action_at = Carbon::now();
        $task->category  = Task::EXPIRED_LESSON;
        // Save
        $student->tasks()->save($task); 
    }

}

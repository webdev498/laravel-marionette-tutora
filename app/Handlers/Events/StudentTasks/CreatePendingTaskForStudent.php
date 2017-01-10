<?php

namespace App\Handlers\Events\StudentTasks;

use App\Events\LessonBookingWasMade;
use App\Handlers\Events\EventHandler;
use App\LessonBooking;
use App\Repositories\EloquentLessonBookingRepository;
use App\Task;
use Carbon\Carbon;


class CreatePendingTaskForStudent extends EventHandler
{
    protected $bookings;

    /**
     * Create the event handler.
     *
     * @param  EloquentLessonBookingRepository $bookings
     * @return void
     */
    public function __construct(EloquentLessonBookingRepository $bookings)
    {
        $this->bookings = $bookings;
    }

    /**
     * Handle the event.
     *
     * @param  UserProfileWasMadeLive  $event
     * @return void
     */
    public function handle(LessonBookingWasMade $event)
    {
        // Lookups
        $booking = $event->booking;
        $lesson = $booking->lesson;
        $relationship = $lesson->relationship;
        $student = $relationship->student;

        // Only create task on pending lessons
        if ($booking->status !== LessonBooking::PENDING) {
            return;
        }

        // Check to see if there is already a pending task for this relationship
        $tasks = $student->tasks()->where('category', Task::PENDING_LESSON)->get();
        
        if ($tasks->count() != 0) {
            $task = $tasks->first();
            
            $actionAt = $this->getActionAtTime($booking);
            if ($actionAt < $task->action_at) {
                $task->action_at = $actionAt;
                $task->save();
            }

            return;

        }

        // Create Task 
        $task = new Task();
        
        $task->body      = 'PENDING - Get to confirmed';
        $task->action_at = $this->getActionAtTime($booking);
        $task->category  = Task::PENDING_LESSON;

        // Save
        $student->tasks()->save($task);
        
    }

    protected function getActionAtTime(LessonBooking $booking)
    {
        $startAt = $booking->start_at;

        $now = Carbon::now();

        if ($now > ($startAt->copy()->subDays(config('tasks.students.pending_task_period')))) {
            $actionAt = $now;
        } else {
            $actionAt = $startAt->copy()->subDays(config('tasks.students.pending_task_period'));
        }

        return $actionAt;
    }

}

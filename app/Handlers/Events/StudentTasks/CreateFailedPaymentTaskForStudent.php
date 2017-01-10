<?php

namespace App\Handlers\Events\StudentTasks;

use App\Events\LessonBookingChargeAuthorisationFailed;
use App\Handlers\Events\EventHandler;
use App\LessonBooking;
use App\Repositories\EloquentLessonBookingRepository;
use App\Task;
use Carbon\Carbon;


class CreateFailedPaymentTaskForStudent extends EventHandler
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
    public function handle(LessonBookingChargeAuthorisationFailed $event)
    {
        // Lookups
        $booking = $event->booking;
        $lesson = $booking->lesson;
        $relationship = $lesson->relationship;
        $student = $relationship->student;

        // Check to see if a failed payment task already exists for this student
        $tasks = $student->tasks()->where('category', Task::FAILED_PAYMENT)->count();

        if ($tasks) return;

        // If we haven't tried this a few times yet, return.
        if ($booking->payment_attempts < config('lessons.max_retry_payment_attempts')) return;

        // Create Task 
        $task = new Task();
        
        $task->body      = 'FAILED PAYMENT';
        $task->action_at = Carbon::now();
        $task->category  = Task::FAILED_PAYMENT;
        // Save
        $student->tasks()->save($task); 
        
    }

}

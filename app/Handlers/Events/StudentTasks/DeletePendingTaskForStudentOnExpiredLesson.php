<?php namespace App\Handlers\Events\StudentTasks;

use App\Events\LessonBookingHasExpired;
use App\Handlers\Events\EventHandler;
use App\LessonBooking;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Student;
use App\Task;

class DeletePendingTaskForStudentOnExpiredLesson extends EventHandler
{
    protected $bookings;


    /**
     * Create the event handler.
     *
     * @param  LessonBookingRepositoryInterface $bookings
     * @return void
     */
    public function __construct(LessonBookingRepositoryInterface $bookings)
    {
        
        $this->bookings = $bookings;
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

        // Check whether student still has pending lessons
        $pendingLessons = $this->bookings->countByStudentByStatus($student, LessonBooking::PENDING);

        if ($pendingLessons != 0 ) return;

        // Check to see if there is already a pending task for this relationship
        $tasks = $student->tasks()->where('category', Task::PENDING_LESSON)->get();

        if ($tasks) {
            foreach ($tasks as $task)
            {
                $task->delete();
            }
        }


    }

}

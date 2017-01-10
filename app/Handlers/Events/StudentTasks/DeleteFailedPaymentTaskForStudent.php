<?php

namespace App\Handlers\Events\StudentTasks;

use App\Events\LessonBookingChargeAuthorisationFailed;
use App\Events\LessonBookingChargeAuthorisationSucceeded;
use App\Handlers\Events\EventHandler;
use App\LessonBooking;
use App\Repositories\EloquentLessonBookingRepository;
use App\Task;
use Carbon\Carbon;


class DeleteFailedPaymentTaskForStudent extends EventHandler
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
    public function handle(LessonBookingChargeAuthorisationSucceeded $event)
    {
        // Lookups
        $booking = $event->booking;
        $lesson = $booking->lesson;
        $relationship = $lesson->relationship;
        $student = $relationship->student;

        // Check to see if a failed payment task for this relationship already exists
        $tasks = $student->tasks()->where('category', Task::FAILED_PAYMENT)->get();

        $bookings = $this->bookings->getByStudentByStatus($student, LessonBooking::AUTHORISATION_FAILED, 1, 100);

        if($bookings->count() != 0) 
        {
            return;
        }
        
        foreach ($tasks as $task)
        {
            $task->delete();
        }
        
    }

}

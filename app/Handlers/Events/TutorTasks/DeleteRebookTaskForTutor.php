<?php

namespace App\Handlers\Events\TutorTasks;

use App\Events\LessonBookingWasMade;
use App\Handlers\Events\EventHandler;
use App\LessonBooking;
use App\Relationship;
use App\Repositories\EloquentLessonBookingRepository;
use App\Task;
use Carbon\Carbon;


class DeleteRebookTaskForTutor extends EventHandler
{
    protected $bookings;

    /**
     * Create the event handler.
     *
     * @param  Newsletter $newsletter
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
        $tutor = $relationship->tutor;

        // Check to see if a rebook task for this relationship already exists
        $tasks = $relationship->tasks()->where('category', Task::REBOOK)->get();
        
        if($tasks) {
            foreach ($tasks as $task)
            {
                $task->delete();
            }
        }
        
    }

}

<?php

namespace App\Handlers\Events\TutorTasks;

use App\Events\LessonBookingWasCancelled;
use App\Handlers\Events\EventHandler;
use App\LessonBooking;
use App\Relationship;
use App\Repositories\EloquentLessonBookingRepository;
use App\Task;
use App\Tasks\TutorTasksTrait;
use Carbon\Carbon;


class UpdateTutorTasksForRelationshipOnLessonCancelled extends EventHandler
{
    
    use TutorTasksTrait;

    /**
     * Handle the event.
     *
     * @param  UserProfileWasMadeLive  $event
     * @return void
     */
    public function handle(LessonBookingWasCancelled $event)
    {
        // Lookups
        $booking = $event->booking;
        $lesson = $booking->lesson;
        $relationship = $lesson->relationship;
        $tutor = $relationship->tutor;
        $this->updateTasksForRelationshipOnLessonCancelled($relationship, $relationship->is_confirmed);
       
    }

}

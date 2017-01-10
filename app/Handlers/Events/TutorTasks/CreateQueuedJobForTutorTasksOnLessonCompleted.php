<?php

namespace App\Handlers\Events\TutorTasks;

use App\Events\LessonBookingWasCompleted;
use App\Handlers\Events\EventHandler;
use App\Jobs\CreateTutorTasksOnLessonCompleted;
use App\LessonBooking;
use App\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;


class CreateQueuedJobForTutorTasksOnLessonCompleted extends EventHandler
{
    use DispatchesJobs;

    protected $bookings;

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasCompleted  $event
     * @return void
     */
    public function handle(LessonBookingWasCompleted $event)
    {
        $job = (new CreateTutorTasksOnLessonCompleted($event->booking))->delay(config('tasks.tutors.first_lesson_period')*24*60*60);

        $this->dispatch($job);
    }
}

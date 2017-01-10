<?php

namespace App\Handlers\Events\StudentTasks;

use App\Events\StudentNotReplied;
use App\Events\StudentWasMismatched;
use App\Handlers\Events\EventHandler;
use App\Task;
use Carbon\Carbon;


class CreateMismatchedTaskForStudent extends EventHandler
{

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  StudentWasMismatched  $event
     * @return void
     */
    public function handle(StudentWasMismatched $event)
    {
        // Lookups
        $student = $event->student;
        $jobs = $student->jobs()->recent()->wasLive()->get();

        // Case - no job
        if ($jobs->count() == 0) {

            $student->updateTasksForMismatchedNoJob();
        }

        // Case - has job - this is handled by the job created reminders. 


        
    }

}

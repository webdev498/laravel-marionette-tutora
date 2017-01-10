<?php

namespace App\Handlers\Events\StudentTasks;

use App\Events\StudentMessageLineWasWritten;
use App\Handlers\Events\EventHandler;
use App\Student;
use App\Task;
use Carbon\Carbon;


class DeleteNotRepliedTasksForStudent extends EventHandler
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
     * @param  UserProfileWasMadeLive  $event
     * @return void
     */
    public function handle(StudentMessageLineWasWritten $event)
    {
        // Lookups
        $line = $event->line;
        $message = $line->message;
        $relationship = $message->relationship;
        $student = $relationship->student;

        // If the student has sent a message, then we can definitely delete the not replied tasks

        $notRepliedTasks = $student->tasks()->where('category', '=', Task::NOT_REPLIED)->get();

        foreach ($notRepliedTasks as $task) {
            $task->delete();
        }

        // If the student has replied to a job enquiry, or it was a new enquiry, we can delete the MISMATCHED_HAS_JOB and MISMATCHED_NO_JOB tasks

        $first_message = $message->lines()->first();
        $job = $first_message->jobs()->first();

        if (($job && $job->user->id == $student->id) || $line->isFirst()) {
            $mismatchedHasJobTasks = $student->tasks()->where(
                function ($query) {
                  $query->where('category', '=', Task::MISMATCHED_HAS_JOB)
                        ->orWhere('category', '=', Task::MISMATCHED_NO_JOB);
                })->get();

            foreach ($mismatchedHasJobTasks as $task) {
                $task->delete();
            }
        }
        
    }

}

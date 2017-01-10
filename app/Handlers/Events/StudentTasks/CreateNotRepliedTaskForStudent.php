<?php

namespace App\Handlers\Events\StudentTasks;

use App\Events\StudentNotReplied;
use App\Handlers\Events\EventHandler;
use App\Students\StudentStatusCalculator;
use Carbon\Carbon;


class CreateNotRepliedTaskForStudent extends EventHandler
{

    /**
     * Handle the event.
     *
     * @param  UserProfileWasMadeLive  $event
     * @return void
     */
    public function handle(StudentNotReplied $event)
    {
        // Lookups
        $line = $event->line;
        $message = $line->message;
        $relationship = $message->relationship;
        $student = $event->student;

        $calc = new StudentStatusCalculator($student);
        
        if ($calc->isNotReplied()) {
            $student->updateTasksForNotReplied($student);
        }
        
    }

}

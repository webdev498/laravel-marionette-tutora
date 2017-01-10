<?php

namespace App\Handlers\Events\TutorTasks;

use App\Events\UserWasBlocked;
use App\Handlers\Events\EventHandler;
use App\Task;
use App\Tutor;
use Carbon\Carbon;


class CreateTutorTaskOnTutorBlocked extends EventHandler
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
    public function handle(UserWasBlocked $event)
    {
        $user = $event->user;

        // Only tutors
        if (! $user instanceof Tutor) return;

        // Check for existing tasks

        $task = new Task;
        $task->body = 'BLOCKED: Rematch Students';
        $task->action_at = Carbon::now()->addDays(2);


        $user->tasks()->save($task);
    }

}

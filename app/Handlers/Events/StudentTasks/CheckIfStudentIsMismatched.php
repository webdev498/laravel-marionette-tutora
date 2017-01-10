<?php

namespace App\Handlers\Events\StudentTasks;

use App\Events\RelationshipWasMismatched;
use App\Events\StudentWasMismatched;
use App\Handlers\Events\EventHandler;
use App\Relationship;
use App\Students\StudentStatusCalculator;
use App\Task;
use Carbon\Carbon;


class CheckIfStudentIsMismatched extends EventHandler
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
    public function handle(RelationshipWasMismatched $event)
    {
        // Lookups
        $relationship = $event->relationship;
        $student = $relationship->student;
        // Check to see if other relationships are also mismatched or not replied

        $calc = new StudentStatusCalculator($student);
        if ($calc->isMismatched()) {
            event(new StudentWasMismatched($student));
        }
               
    }

}

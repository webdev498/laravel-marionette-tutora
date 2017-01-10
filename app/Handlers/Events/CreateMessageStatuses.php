<?php

namespace App\Handlers\Events;

use App\MessageStatus;
use App\Events\RelationshipWasMade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateMessageStatuses
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
     * @param  RelationshipWasMade  $event
     * @return void
     */
    public function handle(RelationshipWasMade $event)
    {
        $relationship = $event->relationship;
        $message = $relationship->message;
        $tutor = $relationship->tutor;
        $student = $relationship->student;

        $statusForTutor = MessageStatus::make($message, $tutor);
        $statusForTutor->save();

        $statusForStudent = MessageStatus::make($message, $student); 
        $statusForStudent->save();
    }
}

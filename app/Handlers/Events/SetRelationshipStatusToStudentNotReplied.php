<?php namespace App\Handlers\Events;

use App\Relationship;
use App\Events\StudentNotReplied;
use App\Repositories\Contracts\RelationshipRepositoryInterface;

class SetRelationshipStatusToStudentNotReplied extends EventHandler
{

    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;

    /**
     * Crete an instance of the event handler
     *
     * @param RelationshipRepositoryInterface $relationships
     * @return void
     */
    public function __construct(RelationshipRepositoryInterface $relationships)
    {
        $this->relationships = $relationships;
    }

    /**
     * Handle the event.
     *
     * @param  LessonWasConfirmed $event
     * @return void
     */
    public function handle(StudentNotReplied $event)
    {
        // Lookups
        $student        = $event->student;
        $line           = $event->line;
        $message        = $line->message;
        $relationship   = $message->relationship;
        // Update
        $relationship->status = Relationship::NO_REPLY_STUDENT;
        // Save
        $this->relationships->save($relationship);
        // Dispatch
        $this->dispatchFor($relationship);
    }

}

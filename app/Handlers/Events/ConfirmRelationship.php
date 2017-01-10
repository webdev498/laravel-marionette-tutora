<?php namespace App\Handlers\Events;

use App\Relationship;
use App\LessonBooking;
use App\Events\LessonWasConfirmed;
use App\Repositories\Contracts\RelationshipRepositoryInterface;

class ConfirmRelationship extends EventHandler
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
    public function handle(LessonWasConfirmed $event)
    {
        // Lookups
        $lesson       = $event->lesson;
        $relationship = $lesson->relationship;
        // Update
        $relationship = Relationship::confirm($relationship);
        // Save
        $this->relationships->save($relationship);
        // Dispatch
        $this->dispatchFor($relationship);
    }

}

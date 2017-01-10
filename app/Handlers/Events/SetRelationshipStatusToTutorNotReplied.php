<?php namespace App\Handlers\Events;

use App\Events\RelationshipWasMismatched;
use App\Events\TutorNotReplied;
use App\Relationship;
use App\Repositories\Contracts\RelationshipRepositoryInterface;
use App\Tutor;

class SetRelationshipStatusToTutorNotReplied extends EventHandler
{

    /**
     * @var UserProfile $profiles
     */
    protected $profiles;
    private $messages;

    /**
     * Create the event handler.
     *
     * @param  UserProfileRepositoryInterface $profiles
     * @return void
     */
    public function __construct(RelationshipRepositoryInterface $relationships) {
        $this->relationships = $relationships;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasCompleted $event
     * @return void
     */
    public function handle(TutorNotReplied $event)
    {
        $tutor = $event->tutor;
        $line = $event->line;
        $message = $line->message;
        $relationship = $message->relationship;

        // Increment if message lines written by tutor are zero

        // Update
        $relationship->status = Relationship::NO_REPLY;
        // Save
        $this->relationships->save($relationship);
        event(new RelationshipWasMismatched($relationship));
        // Dispatch
        $this->dispatchFor($relationship);

        
    }


}

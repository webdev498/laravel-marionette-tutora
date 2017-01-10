<?php

namespace App\Events;

use App\Events\Event;
use App\UserRequirement;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BroadcastUserRequirementWasCompleted extends Event implements ShouldBroadcast
{

    use SerializesModels;

    /**
     * @var UserRequirement
     */
    public $requirement;

    /**
     * @var UserProfile
     */
    public $profile;

    /**
     * @var IdentityDocument
     */
    public $identity_document;

    /**
     * Create a new event instance.
     *
     * @param  UserRequirement $lesson
     * @return void
     */
    public function __construct(UserRequirement $requirement)
    {
        $tutor         = $requirement->tutor;
        $requirements  = $tutor->requirements;

        $this->requirement       = $requirement;
        $this->profile           = $tutor->profile;
        $this->identity_document = $tutor->identity_document;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['user.'.$this->requirement->tutor->uuid];
    }

    /**
    * Get the broadcast event name.
    *
    * @return array
    */
    public function broadcastAs()
    {
        return 'user_requirement_was_completed';
    }
}

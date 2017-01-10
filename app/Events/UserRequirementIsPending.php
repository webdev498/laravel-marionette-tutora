<?php

namespace App\Events;

use App\Events\Event;
use App\UserRequirement;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserRequirementIsPending extends Event implements ShouldBroadcast
{

    use SerializesModels;

    /**
     * @var UserRequirement
     */
    public $requirement;

    /**
     * Create a new event instance.
     *
     * @param  UserRequirement $lesson
     * @return void
     */
    public function __construct(UserRequirement $requirement)
    {
        $this->requirement = $requirement;
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
        return 'user_requirement_is_pending';
    }
}

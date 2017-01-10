<?php

namespace App\Events;

use App\Events\Event;
use App\UserRequirement;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserRequirementIsIncomplete extends Event implements ShouldBroadcast
{

    use SerializesModels;

    /**
     * @var UserRequirement
     */
    public $requirement;

    /**
     * @var mixed
     */
    public $meta;

    /**
     * Create a new event instance.
     *
     * @param  UserRequirement $lesson
     * @return void
     */
    public function __construct(UserRequirement $requirement)
    {
        $this->requirement = $requirement;
        $this->meta        = $requirement->meta;
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
        return 'user_requirement_is_incomplete';
    }
}

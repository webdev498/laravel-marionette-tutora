<?php

namespace App\Events;

use App\Events\Event;
use App\UserRequirement;
use Illuminate\Queue\SerializesModels;

class UserRequirementWasCompleted extends Event
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
}

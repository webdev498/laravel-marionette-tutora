<?php

namespace App\Events;

use App\UserProfile;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class UserProfileWasRejected extends Event
{
    use SerializesModels;

    public $profile;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UserProfile $profile)
    {
        $this->profile = $profile;
    }

}

<?php

namespace App\Events;

use App\Events\Event;
use App\UserProfile;
use Illuminate\Queue\SerializesModels;

class UserProfileWasMadeLive extends Event
{
    use SerializesModels;

    public $profile;

    /**
     * Create a new event instance.
     *
     * @param Userprofile $profile
     * @return void
     */
    public function __construct(Userprofile $profile)
    {
        $this->profile = $profile;
    }

}


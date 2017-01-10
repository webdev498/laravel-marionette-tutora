<?php

namespace App\Handlers\Events;

use App\UserProfile;
use App\Events\UserProfileWasSubmitted;
use App\Events\UserProfileEventInterface;
use App\Events\BroadcastUserRequirements;

class RequestNextUserProfileRequirements extends EventHandler
{

    /**
     * Handle the event.
     *
     * @param  UserProfileEventInterface $event
     * @return void
     */
    public function handle(UserProfileEventInterface $event)
    {
        $profile = $event->getProfile();

        $this->dispatch(new BroadcastUserRequirements($profile->tutor));
    }

}

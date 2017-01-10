<?php

namespace App\Handlers\Events;

use App\Events\UserProfileWasCompleted;
use App\UserProfile;
use App\UserRequirement;
use App\Events\UserProfileWasSubmitted;
use App\Events\UserRequirementWasCompleted;
use App\Events\BroadcastUserRequirementWasCompleted;

class CheckIfUserProfileIsComplete extends EventHandler
{

    /**
     * Handle the event.
     *
     * @param  UserRequirementWasCompleted $event
     * @return void
     */
    public function handle(UserRequirementWasCompleted $event)
    {
        $requirement  = $event->requirement;
        $tutor        = $requirement->tutor;
        $profile      = $tutor->profile;
        $requirements = $tutor->requirements;

        if (
            $profile->required === UserRequirement::PROFILE_INFORMATION &&
            $requirements->areCompleted() === true
        ) {

            $profile = UserProfile::complete($profile);
            $profile->save();

            $this->dispatchFor($profile);
        }

        event(new BroadcastUserRequirementWasCompleted($requirement));
    }

}


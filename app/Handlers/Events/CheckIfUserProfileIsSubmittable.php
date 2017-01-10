<?php

namespace App\Handlers\Events;

use App\UserProfile;
use App\UserRequirement;
use App\Events\UserProfileWasSubmitted;
use App\Events\UserRequirementWasCompleted;
use App\Events\BroadcastUserRequirementWasCompleted;

class CheckIfUserProfileIsSubmittable extends EventHandler
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
            $profile->required === UserRequirement::PROFILE_SUBMIT  &&
            $requirements->areCompleted() === true &&
            $requirements->areCompletedFor(UserRequirement::PROFILE_SUBMIT)

        ) {
            $profile = UserProfile::submit($profile);
            $profile->save();
            event(new UserProfileWasSubmitted($profile));
        }

        event(new BroadcastUserRequirementWasCompleted($requirement));
    }

}


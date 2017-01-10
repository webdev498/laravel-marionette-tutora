<?php

namespace App\Handlers\Events;

use App\UserProfile;
use App\UserRequirement;
use App\Events\UserProfileWasEdited;
use App\Events\UserProfileWasSubmitted;

class CheckIfUserProfileNeedsReviewing extends EventHandler
{

    /**
     * Handle the case where a user has completed their profile, but has been moved to expired.
     *
     * @param  UserRequirementWasCompleted $event
     * @return void
     */
    public function handle(UserProfileWasEdited $event)
    {

        $profile      = $event->profile;
        $tutor        = $profile->tutor;
        $requirements = $tutor->requirements;

        if (
            $profile->required === UserRequirement::PAYOUTS  &&
            $requirements->areCompleted() === true &&
            $profile->status === UserProfile::EXPIRED
        ) {
            $profile = UserProfile::submit($profile);
            $profile->save();
            event(new UserProfileWasSubmitted($profile));
        }
    }
}

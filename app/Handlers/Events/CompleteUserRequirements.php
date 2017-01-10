<?php

namespace App\Handlers\Events;

use App\Tutor;
use App\UserProfile;
use App\UserRequirement;
use App\IdentityDocument;
use App\Events\UserWasEdited;
use App\Events\DispatchesEvents;
use App\Events\QuizWasSubmitted;
use Illuminate\Support\Collection;
use App\Events\UserProfileWasEdited;
use App\Events\IdentityDocumentWasInspected;

class CompleteUserRequirements extends EventHandler
{
    use DispatchesEvents;

    /**
     * Handle the event.
     *
     * @param  UserWasEdited $event
     *
     * @return void
     */
    public function handle($event)
    {
        $method = 'handle' . class_basename($event);

        if (method_exists(
            $this,
            $method
        )) {
            $this->{$method}($event);
        }
    }

    /**
     * Handle the event.
     *
     * @param  UserProfileWasEdited $event
     *
     * @return void
     */
    protected function handleUserProfileWasEdited(
        UserProfileWasEdited $event
    ) {
        $profile      = $event->profile;
        $tutor        = $profile->tutor;
        $requirements = $tutor->requirements;

        $this->completeRequirements(
            $requirements,
            $tutor
        );
    }

    /**
     * Handle the event.
     *
     * @param  UserWasEdited $event
     *
     * @return void
     */
    protected function handleUserWasEdited(
        UserWasEdited $event
    ) {
        $user = $event->user;

        if ($user instanceof Tutor) {
            $requirements = $user->requirements;

            $this->completeRequirements(
                $requirements,
                $user
            );
        }
    }

    /**
     * Handle the event.
     *
     * @param  QuizWasSubmitted $event
     *
     * @return void
     */
    protected function handleQuizWasSubmitted(
        QuizWasSubmitted $event
    ) {
        $profile      = $event->profile;
        $tutor        = $profile->tutor;
        $requirements = $tutor->requirements;

        $this->completeQuizRequirement(
            $requirements,
            $tutor
        );
    }

    /**
     * Handle the event.
     *
     * @param  UserWasEdited $event
     *
     * @return void
     */
    protected function handleIdentityDocumentWasInspected(
        IdentityDocumentWasInspected $event
    ) {
        $identityDocument = $event->identityDocument;
        $tutor            = $identityDocument->user;
        $requirements     = $tutor->requirements;

        $this->completeRequirements(
            $requirements,
            $tutor
        );
    }

    /**
     * Complete the given requirements by a given means
     *
     * @param  Collection $requirements
     * @param  Tutor      $tutor
     *
     * @return vois
     */
    protected function completeRequirements(
        Collection $requirements,
        Tutor $tutor
    ) {
        $requirements = $requirements->where(
            'is_completed',
            false
        );

        foreach ($requirements as $requirement) {
            $method = camel_case("{$requirement->section}_{$requirement->name}_requirement_should_be_completed");

            loginfo($method);

            if (method_exists(
                $this,
                $method
            )) {
                if ($this->{$method}(
                    $requirement,
                    $tutor
                )
                ) {
                    $requirement = UserRequirement::complete($requirement);
                    $requirement->save();

                    $this->dispatchFor($requirement);
                }
            }
        }
    }

    /**
     * Complete the quiz requirement
     *
     * @param  Collection $requirements
     * @param  Tutor      $tutor
     *
     * @return vois
     */
    protected function completeQuizRequirement(
        Collection $requirements,
        Tutor $tutor
    ) {

        $requirement = $requirements->where(
            'name',
            UserRequirement::QUIZ_QUESTIONS
        )->first();

        $requirement = UserRequirement::complete($requirement);
        $requirement->save();

        $this->dispatchFor($requirement);
    }

    /**
     * Should the tagline requirement for the profile be completed?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     *
     * @return boolean
     */
    protected function profileTaglineRequirementShouldBeCompleted(
        UserRequirement $requirement,
        Tutor $tutor
    ) {
        return $tutor->profile->tagline !== null;
    }

    /**
     * Should the rate requirement for the profile be completed?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     *
     * @return boolean
     */
    protected function profileRateRequirementShouldBeCompleted(
        UserRequirement $requirement,
        Tutor $tutor
    ) {
        return $tutor->profile->rate !== null;
    }

    /**
     * Should the bio requirement for the profile be completed?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     *
     * @return boolean
     */
    protected function profileBioRequirementShouldBeCompleted(
        UserRequirement $requirement,
        Tutor $tutor
    ) {
        return !empty($tutor->profile->bio);
    }

    /**
     * Should the bio requirement for the profile be completed?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     *
     * @return boolean
     */
    protected function profileProfilePictureRequirementShouldBeCompleted(
        UserRequirement $requirement,
        Tutor $tutor
    ) {
        $filename = templ(
            ':path/app/profile-pictures/:uuid@:sizex:size.:ext',
            [
                'path' => storage_path(),
                'uuid' => $tutor->uuid,
                'size' => 180,
                'ext'  => 'jpg',
            ]
        );

        return file_exists($filename);
    }

    /**
     * Should the bio requirement for the profile be completed?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     *
     * @return boolean
     */
    protected function profileTravelPolicyRequirementShouldBeCompleted(
        UserRequirement $requirement,
        Tutor $tutor
    ) {
        return $tutor->profile->travel_radius !== null
        && $tutor->addresses->default->postcode !== null;
    }

    /**
     * Should the students requirement for the profile be completed?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     *
     * @return boolean
     */
    protected function profileSubjectsRequirementShouldBeCompleted(
        UserRequirement $requirement,
        Tutor $tutor
    ) {
        return $tutor->subjects->count() > 0;
    }

    /**
     * Should the universities requirement for the profile be completed?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     *
     * @return boolean
     */
    protected function profileQualificationsRequirementShouldBeCompleted(
        UserRequirement $requirement,
        Tutor $tutor
    ) {
        return $tutor->qualificationUniversities->count() > 0
        || $tutor->qualificationAlevels->count() > 0
        || $tutor->qualificationOthers->count() > 0;
    }

    /**
     * Should the qts requirement for the profile be completed?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     *
     * @return boolean
     */
    protected function profileQualifiedTeacherStatusRequirementShouldBeCompleted(
        UserRequirement $requirement,
        Tutor $tutor
    ) {
        return $tutor->qualificationTeacherStatus !== null;
    }

    /**
     * Should the identity document requirement for the account be completed?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     *
     * @return boolean
     */
    protected function accountIdentificationRequirementShouldBeCompleted(
        UserRequirement $requirement,
        Tutor $tutor
    ) {
        if ($tutor->identityDocument) {
            return in_array(
                $tutor->identityDocument->status,
                [
                    'stored',
                    'sent',
                    'verified',
                    'pending',
                ]
            );
        }
    }

    /**
     * Should the accoutn paymentment requirement for the account be completed?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     *
     * @return boolean
     */
    protected function accountPaymentDetailsRequirementShouldBeCompleted(
        UserRequirement $requirement,
        Tutor $tutor
    ) {
        return $tutor->last_four !== null
        && $tutor->addresses->billing->postcode !== null;
    }

    /**
     * Check if the requirement for the personal video should be completed.
     *
     * @param  UserRequirement $requirement
     *
     * @param Tutor            $tutor
     *
     * @return bool
     */
    protected function otherPersonalVideoRequirementShouldBeCompleted(
        UserRequirement $requirement,
        Tutor $tutor
    ) {
        return $tutor->profile->video_status !== null
        && $tutor->profile->video_status !== 'canceled'
        && $tutor->profile->video_status !== 'deleted';
    }
}

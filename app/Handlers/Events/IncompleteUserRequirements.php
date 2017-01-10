<?php

namespace App\Handlers\Events;

use App\Tutor;
use App\UserProfile;
use App\UserRequirement;
use App\IdentityDocument;
use App\Events\UserWasEdited;
use App\Events\DispatchesEvents;
use Illuminate\Support\Collection;
use App\Events\UserProfileWasEdited;
use App\Events\IdentityDocumentWasInspected;

class IncompleteUserRequirements extends EventHandler
{
    use DispatchesEvents;

    /**
     * Handle the event.
     *
     * @param  UserWasEdited $event
     * @return void
     */
    public function handle($event)
    {
        $method = 'handle'.class_basename($event);

        if (method_exists($this, $method)) {
            $this->{$method}($event);
        }
    }

    /**
     * Handle the event.
     *
     * @param  UserWasEdited $event
     * @return void
     */
    protected function handleIdentityDocumentWasInspected(
        IdentityDocumentWasInspected $event
    ) {
        $identityDocument = $event->identityDocument;
        $tutor            = $identityDocument->user;
        $requirements     = $tutor->requirements;

        $this->incompleteRequirements($requirements, $tutor);
    }

    /**
     * Incomplete the given requirements by a given means
     *
     * @param  Collection $requirements
     * @param  Tutor      $tutor
     * @return vois
     */
    protected function incompleteRequirements(
        Collection $requirements,
        Tutor      $tutor
    ) {
        $requirements = $requirements;

        foreach ($requirements as $requirement) {
            $method = camel_case("{$requirement->section}_{$requirement->name}_requirement_should_be_incompleted");

            if (method_exists($this, $method)) {
                if ($this->{$method}($requirement, $tutor)) {
                    $requirement = UserRequirement::incomplete($requirement);
                    $requirement->save();

                    $this->dispatchFor($requirement);
                }
            }
        }
    }

    /**
     * Should the identity document requirement for the account be incomplete?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     * @return boolean
     */
    protected function accountIdentificationRequirementShouldBeIncompleted(
        UserRequirement $requirement,
        Tutor           $tutor
    ) {
        if ($tutor->identityDocument) {
            $requirement->meta = [
                'identity_document' => [
                    'status'  => $tutor->identityDocument->status,
                    'details' => $tutor->identityDocument->details,
                ],
            ];

            return $tutor->identityDocument->status === 'unverified';
        }
    }
}

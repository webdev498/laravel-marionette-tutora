<?php

namespace App\Handlers\Events;

use App\Tutor;
use App\UserRequirement;
use App\IdentityDocument;
use App\Events\DispatchesEvents;
use Illuminate\Support\Collection;
use App\Events\IdentityDocumentWasSent;

class PendUserRequirements extends EventHandler
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

        loginfo($method);

        if (method_exists($this, $method)) {
            $this->{$method}($event);
        }
    }

    /**
     * Handle the event.
     *
     * @param  UserWasLegalEdited $event
     * @return void
     */
    protected function handleIdentityDocumentWasSent(
        IdentityDocumentWasSent $event
    ) {
        $identityDocument = $event->identityDocument;
        $tutor            = $identityDocument->user;
        $requirements     = $tutor->requirements;

        $this->pendRequirements($requirements, $tutor);
    }

    /**
     * Pend the given requirements by a given means
     *
     * @param  Collection $requirements
     * @param  Tutor      $tutor
     * @return vois
     */
    protected function pendRequirements(
        Collection $requirements,
        Tutor      $tutor
    ) {
        $requirements = $requirements
            ->where('is_pending', false)
            ->where('is_completed', false);

        foreach ($requirements as $requirement) {
            $method = camel_case("{$requirement->section}_{$requirement->name}_requirement_should_be_pending");

            if (method_exists($this, $method)) {
                if ($this->{$method}($requirement, $tutor)) {
                    $requirement = UserRequirement::pend($requirement);
                    $requirement->save();

                    $this->dispatchFor($requirement);
                }
            }
        }
    }

    /**
     * Should the identity document requirement for the account be pending?
     *
     * @param  UserRequirement $requirement
     * @param  Tutor           $tutor
     * @return boolean
     */
    protected function accountIdentificationRequirementShouldBePending(
        UserRequirement $requirement,
        Tutor           $tutor
    ) {
        if ($tutor->identityDocument) {
            return in_array($tutor->identityDocument->status, [
                'stored'
            ]);
        } else {
            return false;
        }
    }

}

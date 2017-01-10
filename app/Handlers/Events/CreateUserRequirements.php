<?php

namespace App\Handlers\Events;

use App\Tutor;
use App\UserRequirement;
use App\Events\UserWasRegistered;

class CreateUserRequirements extends EventHandler
{

    /**
     * Handle the event.
     *
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        $user = $event->user;

        if ($user instanceof Tutor) {
            $requirements = array_map(function ($attributes) {
                return UserRequirement::make($attributes);
            }, config('requirements'));

            $user->requirements()->saveMany($requirements);
        }
    }

}

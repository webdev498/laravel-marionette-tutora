<?php namespace App\Handlers\Events;

use App\Tutor;
use App\UserProfile;
use App\Events\TutorNotReplied;
use App\Events\TutorNoResponseLimitReached;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\UserProfileRepositoryInterface;

class IncrementNoResponseCounter extends EventHandler
{

    /**
     * @var UserProfile $profiles
     */
    protected $profiles;
    private $messages;

    /**
     * Create the event handler.
     *
     * @param  UserProfileRepositoryInterface $profiles
     * @return void
     */
    public function __construct(
        UserProfileRepositoryInterface $profiles,
        MessageRepositoryInterface $messages
    ) {
        $this->profiles   = $profiles;
        $this->messages = $messages;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasCompleted $event
     * @return void
     */
    public function handle(TutorNotReplied $event)
    {
        $tutor = $event->tutor;
        $profile = $tutor->profile;

        // Increment if message lines written by tutor are zero

        $profile->no_response_counter = $profile->no_response_counter + 1;

        $this->profiles->save($profile);

        if ($this->checkNoResponseLimitReached($tutor, $profile)) {

            event(new TutorNoResponseLimitReached($tutor));     
        }
        
    }

    protected function checkNoResponseLimitReached(Tutor $tutor, UserProfile $profile)
    {
        $relationshipCount = $tutor->relationships->count();

        if ($relationshipCount <= 2) {
            $noResponseLimit = 1;
        }
        if ($relationshipCount > 2) {
            $noResponseLimit = 2;
        }

        if ($profile->no_response_counter >= $noResponseLimit && $profile->status != UserProfile::OFFLINE) {
            return true;
        }

        return false;
    }

}

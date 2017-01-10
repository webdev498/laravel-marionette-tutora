<?php

namespace App\Handlers\Events;

use App\Events\LessonBookingWasCompleted;
use App\Repositories\Contracts\UserProfileRepositoryInterface;

class IncrementUsersLessonCount extends EventHandler
{

    /**
     * @var UserProfileRepositoryInterface
     */
    protected $profiles;

    /**
     * Create the event handler.
     *
     * @param  UserProfileRepositoryInterface $profiles
     * @return void
     */
    public function __construct(UserProfileRepositoryInterface $profiles)
    {
        $this->profiles = $profiles;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasCompleted $event
     * @return void
     */
    public function handle(LessonBookingWasCompleted $event)
    {
        $booking      = $event->booking;
        $lesson       = $booking->lesson;
        $relationship = $lesson->relationship;
        $tutor        = $relationship->tutor;
        $profile      = $tutor->profile;

        $profile->lessons_count = $profile->lessons_count + 1;

        $this->profiles->save($profile);
    }

}

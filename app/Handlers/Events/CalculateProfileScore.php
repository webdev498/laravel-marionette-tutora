<?php

namespace App\Handlers\Events;

use App\Events\UserProfileWasAccepted;
use App\Repositories\Contracts\UserProfileRepositoryInterface;
use App\Search\Algorithm\TutorProfileScorer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CalculateProfileScore
{
    
    protected $profiles;

    protected $scorer;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(
        TutorProfileScorer $scorer,
        UserProfileRepositoryInterface $profiles
    ) {
        $this->scorer = $scorer;
        $this->profiles = $profiles;
    }

    /**
     * Handle the event.
     *
     * @param  UserProfileWasMadeLive  $event
     * @return void
     */
    public function handle(UserProfileWasAccepted $event)
    {
        $profile = $event->profile;
        $tutor = $profile->tutor;

        $this->scorer->setTutor($tutor);
        $score = $this->scorer->calculateProfileScore();

        $profile->profile_score = $score;

        $this->profiles->save($profile);
    }
}

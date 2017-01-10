<?php

namespace App\Handlers\Events;

use App\UserProfile;
use App\Events\UserProfileWasMadeLive;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Mailers\Newsletters\Contracts\NewsletterListInterface;

class AddUserToLiveTutorsNewsletter extends EventHandler implements ShouldBeQueued
{


    /**
     * @var Newsletter
     */
    protected $newsletter;

    /**
     * Create the event handler.
     *
     * @param  Newsletter $newsletter
     * @return void
     */
    public function __construct(NewsletterListInterface $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * Handle the event.
     *
     * @param  UserProfileWasMadeLive  $event
     * @return void
     */
    public function handle(UserProfileWasMadeLive $event)
    {
        $profile = $event->profile;
        $tutor = $profile->tutor;

        if (\App::environment('production')) {
            $this->addTutorToLiveTutorsList($tutor);  
        }
    }

    protected function addTutorToLiveTutorsList($tutor)
    {
        
        $this->newsletter->subscribeTo('allTutors', $tutor);
         
        $this->newsletter->updateMember('allTutors', 'TutorType', 'Live', $tutor);
    }
}

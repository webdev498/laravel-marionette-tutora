<?php

namespace App\Handlers\Events;

use App\UserProfile;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Events\UserProfileWasMadeOffline;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Mailers\Newsletters\Contracts\NewsletterListInterface;

class AddUserToOfflineTutorsNewsletter extends EventHandler implements ShouldBeQueued
{

    use DispatchesJobs;

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
    public function handle(UserProfileWasMadeOffline $event)
    {
        
        $profile = $event->profile;
        $tutor = $profile->tutor;

        if (\App::environment('production')) {
            $this->addTutorToOfflineTutorsList($tutor);
        }

    }

    protected function addTutorToOfflineTutorsList($tutor)
    {
        
        $this->newsletter->subscribeTo('allTutors', $tutor); 

        $this->newsletter->updateMember('allTutors', 'TutorType', 'Offline', $tutor);
    }
}

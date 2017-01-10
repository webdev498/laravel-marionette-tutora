<?php

namespace App\Handlers\Events;

use App\UserProfile;
use App\Events\UserProfileWasRejected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Mailers\Newsletters\Contracts\NewsletterListInterface;

class RemoveUserFromTutorsNewsletter extends EventHandler implements ShouldBeQueued
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
    public function handle(UserProfileWasRejected $event)
    {
        
        $profile = $event->profile;
        $tutor = $profile->tutor;

        if (\App::environment('production')) {
            //necessary to add them to newsletter first, then remove
            $this->newsletter->subscribeTo('allTutors', $tutor);

            $this->newsletter->unsubscribeFrom('allTutors', $tutor);
        }

    }
}

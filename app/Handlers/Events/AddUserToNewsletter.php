<?php

namespace App\Handlers\Events;

use App\Tutor;
use App\Student;
use App\Events\UserWasRegistered;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Mailers\Newsletters\Contracts\NewsletterListInterface;

class AddUserToNewsletter extends EventHandler implements ShouldBeQueued
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
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        $user = $event->user;

        if (\App::environment('production')) {
        
            if ($user instanceof Student) {
                $subscribe = $this->newsletter->subscribeTo('allStudents', $user);
            }

            if ($user instanceof Tutor) {
                $subscribe = $this->newsletter->subscribeTo('allTutors', $user);
            }
        }

    }

}
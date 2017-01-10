<?php

namespace App\Handlers\Events\Subscriptions;

use App\Events\UserWasRegistered;
use App\Handlers\Events\EventHandler;
use App\Student;
use App\Tutor;
use App\UserSubscription;
use App\Repositories\Contracts\UserSubscriptionRepositoryInterface;

class CreateUserSubscription extends EventHandler
{
    
    protected $subscriptions;

    /**
     * Create the event handler.
     *
     * @param  UserSubscriptionRepositoryInterface $subscriptions
     * @return void
     */
    public function __construct(UserSubscriptionRepositoryInterface $subscriptions)
    {
        
        $this->subscriptions = $subscriptions;
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

        $subscription = UserSubscription::blank();

        $this->subscriptions->saveForUser($user, $subscription);
    }

}
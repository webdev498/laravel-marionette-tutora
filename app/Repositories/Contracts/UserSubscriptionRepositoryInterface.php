<?php namespace App\Repositories\Contracts;

use App\User;
use App\UserSubscription;

interface UserSubscriptionRepositoryInterface
{
    public function saveForUser(User $user, UserSubscription $subscription);

}

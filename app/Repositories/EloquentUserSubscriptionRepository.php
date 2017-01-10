<?php namespace App\Repositories;

use App\Database\Exceptions\ResourceNotPersistedException;
use App\Repositories\Contracts\UserSubscriptionRepositoryInterface;
use App\User;
use App\UserSubscription;
use DateTime;

class EloquentUserSubscriptionRepository implements UserSubscriptionRepositoryInterface
{
	protected $subscription;

	public function __construct(UserSubscription $subscription)
	{
		$this->subscription = $subscription;
	}

	public function saveForUser(User $user, UserSubscription $subscriptions)
	{
		if ( ! $user->subscription()->save($subscriptions)) {
            throw new ResourceNotPersistedException();
        }

        return $subscriptions;
	}

}
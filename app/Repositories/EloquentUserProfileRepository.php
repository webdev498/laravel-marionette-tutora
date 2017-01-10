<?php namespace App\Repositories;

use App\User;
use App\UserProfile;
use App\Database\Exceptions\ResourceNotPersistedException;
use App\Repositories\Contracts\UserProfileRepositoryInterface;

class EloquentUserProfileRepository implements UserProfileRepositoryInterface
{

    /**
     * @var UserProfile
     */
    protected $profile;

    /**
     * Create a new instance of this repository
     *
     * @param  UserProfile $profile
     * @return void
     */
    public function __construct(UserProfile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Save a profile for a user
     *
     * @param  User        $user
     * @param  UserProfile $profile
     * @return UserProfile
     */
    public function saveForUser(User $user, UserProfile $profile)
    {
        if ( ! $user->profile()->save($profile)) {
            throw new ResourceNotPersistedException();
        }

        return $profile;
    }

    /**
     * Save a user profile
     *
     * @param  UserProfile $profile
     * @return UserProfile
     */
    public function save(UserProfile $profile)
    {
        if ( ! $profile->save()) {
            throw new ResourceNotPersistedException();
        }

        return $profile;
    }

}

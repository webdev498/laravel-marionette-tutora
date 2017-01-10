<?php namespace App\Handlers\Events;

use App\Tutor;
use App\UserProfile;
use App\Events\UserWasRegistered;
use App\Repositories\Contracts\UserProfileRepositoryInterface;

class Test extends EventHandler
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
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        loginfo('called!!');

    }

}

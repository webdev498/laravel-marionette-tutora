<?php namespace App\Events;

use App\UserProfile;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class UserProfileWasSubmitted extends Event implements UserProfileEventInterface
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UserProfile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

}

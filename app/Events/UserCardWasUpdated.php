<?php namespace App\Events;

use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class UserCardWasUpdated extends Event
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

}

<?php namespace App\Events;

use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class UserWasRegistered extends Event
{

    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

}

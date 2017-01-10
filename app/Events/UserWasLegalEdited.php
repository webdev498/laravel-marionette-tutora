<?php namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;

class UserWasLegalEdited extends Event
{

    use SerializesModels;

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

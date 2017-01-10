<?php

namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;

class BackgroundCheckWasRemoved extends Event
{
    use SerializesModels;

    public $owner;
    public $uuid;

    /**
     * Create a new event instance.
     *
     * @param User $owner
     */
    public function __construct(User $owner, $uuid)
    {
        $this->$owner   = $owner;
        $this->uuid     = $uuid;
    }
}


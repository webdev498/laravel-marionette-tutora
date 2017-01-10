<?php namespace App\Events\Jobs;

use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class JobWasRemoved extends Event
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param User $owner
     * @param $uuid
     */
    public function __construct(User $owner, $uuid)
    {
        $this->owner = $owner;
        $this->uuid  = $uuid;
    }

}

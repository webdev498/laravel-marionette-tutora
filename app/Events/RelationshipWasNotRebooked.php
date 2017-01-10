<?php namespace App\Events;

use App\Events\Event;
use App\Relationship;
use Illuminate\Queue\SerializesModels;

class RelationshipWasNotRebooked extends Event
{

    /**
     * Create a new event instance.
     *
     * @param  Relationship $relationship
     * @return void
     */
    public function __construct(Relationship $relationship)
    {
        $this->relationship = $relationship;
    }

}

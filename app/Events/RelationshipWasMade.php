<?php

namespace App\Events;

use App\Relationship;

class RelationshipWasMade extends Event
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

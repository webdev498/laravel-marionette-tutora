<?php

namespace App\Events;

use App\Events\Event;
use App\IdentityDocument;
use Illuminate\Queue\SerializesModels;

class IdentityDocumentWasInspected extends Event
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(IdentityDocument $identityDocument)
    {
        $this->identityDocument = $identityDocument;
    }


}

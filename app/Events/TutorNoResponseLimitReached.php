<?php

namespace App\Events;

use App\Tutor;

class TutorNoResponseLimitReached extends Event
{
    /**
     * Create a new event instance.
     *
     * @param  Tutor $Tutor
     * @return void
     */
    public function __construct(Tutor $tutor)
    {
        $this->tutor = $tutor;
    }
}
<?php

namespace App\Handlers\Events;

use App\Events\BackgroundCheckEventInterface;
use App\Events\Broadcasts\BroadcastTutorBackgroundChecks;

class RequestUserBackgroundChecks extends EventHandler
{

    /**
     * Handle the event.
     *
     * @param  BackgroundCheckEventInterface $event
     *
     * @return void
     */
    public function handle(BackgroundCheckEventInterface $event)
    {
        $background = $event->getBackground();

        $this->dispatch(new BroadcastTutorBackgroundChecks($background->user));
    }

}

<?php

namespace App\Events;

use App\Events\Event;
use App\UserBackgroundCheck;

interface BackgroundCheckEventInterface
{

    /**
     * @return UserBackgroundCheck
     */
    public function getBackground();
}


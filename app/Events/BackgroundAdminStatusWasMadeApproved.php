<?php

namespace App\Events;

use App\Events\Event;
use App\UserBackgroundCheck;
use Illuminate\Queue\SerializesModels;

class BackgroundAdminStatusWasMadeApproved extends Event implements BackgroundCheckEventInterface
{
    use SerializesModels;

    /**
     * @var UserBackgroundCheck
     */
    public $background;

    /**
     * Create a new event instance.
     *
     * @param UserBackgroundCheck $background
     */
    public function __construct(UserBackgroundCheck $background)
    {
        $this->background = $background;
    }

    /**
     * @return UserBackgroundCheck
     */
    public function getBackground()
    {
        return $this->background;
    }

}


<?php namespace App\Commands;

use App\Commands\Command;

class ConfirmLessonBookingCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @param $uuid
     * @param null $card
     */
    public function __construct(
        $uuid,
        $card = null
    )
    {
        $this->uuid      = $uuid;
        $this->card      = $card;
    }

}

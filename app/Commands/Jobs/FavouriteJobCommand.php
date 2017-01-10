<?php namespace App\Commands\Jobs;

use App\User;
use App\Commands\Command;

class FavouriteJobCommand extends Command
{

    /**
     * Create a new command instance
     *
     * @param string $uuid
     * @param User   $user
     */
    public function __construct(
        $uuid,
        $user
    ) {
        $this->uuid = $uuid;
        $this->user = $user;
    }

}

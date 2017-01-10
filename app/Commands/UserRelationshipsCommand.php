<?php namespace App\Commands;

use App\Commands\Command;

class UserRelationshipsCommand extends Command
{
    /**
     * Get user relationships command instance.
     *
     * @param string $uuid
     */
    public function __construct(
        $uuid
    ) {
        $this->uuid = $uuid;
    }

}

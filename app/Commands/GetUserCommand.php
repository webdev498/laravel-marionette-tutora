<?php namespace App\Commands;

class GetUserCommand extends Command
{

    /**
     * Get a user command instance.
     *
     * @return void
     */
    public function __construct(
        $uuid
    ) {
        $this->uuid = $uuid;
    }

}

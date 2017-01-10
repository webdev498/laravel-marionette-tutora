<?php namespace App\Commands;

class GetUserReviewsCommand extends Command
{

    /**
     * Get a user command instance.
     *
     * @return void
     */
    public function __construct(
        $uuid,
        $status
    ) {
        $this->uuid = $uuid;
        $this->deleted = $status;
    }

}

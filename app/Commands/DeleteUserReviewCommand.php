<?php namespace App\Commands;

use App\Commands\Command;

class DeleteUserReviewCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @param $id
     */
    public function __construct(
        $id
    ) {
        $this->id         = $id;
    }

}

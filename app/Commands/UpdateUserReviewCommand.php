<?php namespace App\Commands;

use App\Commands\Command;

class UpdateUserReviewCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @param $rating
     * @param $body
     */
    public function __construct(
        $id,
        $body,
        $rating
    ) {
        $this->id         = $id;
        $this->rating       = $rating;
        $this->body         = $body;
    }

}

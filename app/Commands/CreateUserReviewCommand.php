<?php namespace App\Commands;

use App\Commands\Command;

class CreateUserReviewCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @param $uuid
     * @param $rating
     * @param $body
     * @param $student
     */
    public function __construct(
        $uuid,
        $rating,
        $body,
        $student = null
    ) {
        $this->uuid         = $uuid;
        $this->studentUuid  = $student;
        $this->rating       = $rating;
        $this->body         = $body;
    }

}

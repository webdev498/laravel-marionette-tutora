<?php namespace App\Events;

use App\Events\Event;
use App\UserReview;

class UserReviewWasLeft extends Event
{

    /**
     * Create a new event instance.
     *
     * @param  UserReview $review
     * @return void
     */
    public function __construct(UserReview $review)
    {
        $this->review = $review;
    }

}

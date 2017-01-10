<?php

namespace App\Handlers\Events;

use App\UserReview;
use App\UserProfile;
use App\Events\UserReviewWasLeft;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\UserReviewRepositoryInterface;

class UpdateUserProfileRating extends EventHandler
{
    /**
     * @var UserReviewRepositoryInterface
     */
    protected $reviews;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * Create the event handler.
     *
     * @param  UserReviewRepositoryInterface $reviews
     * @param  UserRepositoryInterface       $users
     * @return void
     */
    public function __construct(
        UserReviewRepositoryInterface $reviews,
        UserRepositoryInterface       $users
    ) {
        $this->reviews = $reviews;
        $this->users   = $users;
    }

    /**
     * Handle the event.
     *
     * @param  UserReviewWasLeft $event
     * @return void
     */
    public function handle(UserReviewWasLeft $event)
    {
        // Lookups
        $review  = $event->review;
        $tutor   = $review->user;
        // Calc
        $rating = $this->reviews->averageRatingByTutor($tutor);
        $count  = $this->reviews->countByTutor($tutor);
        // Update
        UserProfile::rate($tutor->profile, $rating, $count);
        // Save
        $this->users->save($tutor);
    }

}

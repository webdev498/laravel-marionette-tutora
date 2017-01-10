<?php

namespace App\Presenters;

use App\UserReview;

class ReviewPresenter extends AbstractPresenter
{
    protected $defaultIncludes = [
        'reviewer',
    ];

    /**
     * Turn this object into a generic array
     *
     * @param  UserReview $review
     * @return array
     */
    public function transform(UserReview $review)
    {
        return [
            'id' => $review->id,
            'body' => (string) pe($review->body),
            'rating' => $review->rating,
        ];
    }

    protected function includeReviewer(UserReview $review)
    {
        return $this->item($review->reviewer, new UserPresenter());
    }
}

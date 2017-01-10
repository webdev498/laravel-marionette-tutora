<?php namespace App\Transformers;

use App\UserReview;
use League\Fractal\TransformerAbstract;

class UserReviewTransformer extends TransformerAbstract
{

    /**
     * Turn this object into a generic array
     *
     * @param  UserReview $review
     * @return array
     */
    public function transform(UserReview $review)
    {

        $reviewer = $review->reviewer()->first();

        return [
            'id'     => (integer) $review->id,
            'rating' => (integer) $review->rating,
            'body'   => (string)  e($review->body),
            'reviewer_name' => (string) ($reviewer->first_name . ' ' . $reviewer->last_name),
            'reviewer_uuid' => (string) $reviewer->uuid
        ];
    }

}

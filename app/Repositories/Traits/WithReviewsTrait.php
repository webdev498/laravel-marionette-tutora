<?php

namespace App\Repositories\Traits;

trait WithReviewsTrait
{

    /**
     * Select only the reviewer by a given reviewer
     *
     * @param  MorphMany $query
     * @param  integer   $reviewerId
     * @return MorphMany
     */
    protected function withReviewsBy($query, $reviewerId)
    {
        return $query->where('reviewer_id', '=', $reviewerId);
    }

}

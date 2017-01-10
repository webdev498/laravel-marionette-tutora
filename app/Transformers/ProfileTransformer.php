<?php namespace App\Transformers;

use App\UserProfile;
use League\Fractal\ParamBag;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class ProfileTransformer extends TransformerAbstract
{

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(UserProfile $profile)
    {
        return [
            'status'        => $profile->status,
            'admin_status'  => $profile->admin_status,
            'tagline'       => (string)$profile->tagline,
            'rate'          => $profile->rate ? (integer)$profile->rate : null,
            'lessons_count' => (integer)$profile->lessons_count,
            'rating'        => (integer)$profile->rating,
            'ratings_count' => (integer)$profile->ratings_count,
            'short_bio'     => (string)e($profile->short_bio),
            'bio'           => (string)e($profile->bio),
            'travel_radius' => $profile->travel_radius !== null ? (integer)$profile->travel_radius : -1,
            'video_status'  => $profile->video_status
        ];
    }

}

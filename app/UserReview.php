<?php namespace App;

use App\Database\Model;
use App\Events\UserReviewWasLeft;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserReview extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static function leave($rating, $body, User $reviewer)
    {
        $review = new static();

        $review->rating      = $rating;
        $review->body        = $body;
        $review->reviewer_id = $reviewer->id;

        $review->raise(new UserReviewWasLeft($review));

        return $review;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function reviewer()
    {
        return $this->belongsTo('App\User', 'reviewer_id');
    }

}

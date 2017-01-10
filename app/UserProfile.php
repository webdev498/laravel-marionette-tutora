<?php namespace App;

use App\Database\Model;
use App\Events\UserProfileWasEdited;
use App\Events\UserProfileWasCreated;
use App\Events\UserProfileWasAccepted;
use App\Events\UserProfileWasMadeLive;
use App\Events\UserProfileWasMadeOffline;
use App\Events\UserProfileWasRejected;
use App\Observers\UserProfileObserver;
use App\Events\UserProfileWasSubmitted;
use App\Events\UserProfileWasCompleted;

class UserProfile extends Model
{
    // Statuses
    const SNEW = 'new';
    const SUBMITTABLE = 'submittable';
    const PENDING = 'pending';
    const LIVE = 'live';
    const OFFLINE = 'offline';
    const EXPIRED = 'expired';

    // Admin Statuses
    const REVIEW = 'review';
    const OK = 'ok';
    const REJECTED = 'rejected';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'live',
        'tagline',
        'rate',
        'bio',
        'short_bio',
        'travel_radius',
        'video_status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'quality',
        'user_id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'featured' => 'boolean',
    ];

    /**
     * Create a blank profile.
     *
     * @return UserProfile
     */
    public static function blank()
    {
        // Attributes
        $profile               = new static();
        $profile->status       = static::SNEW;
        $profile->admin_status = static::PENDING;
        $profile->required     = UserRequirement::PROFILE_INFORMATION;
        // Raise
        $profile->raise(new UserProfileWasCreated($profile));

        // Return
        return $profile;
    }

    /**
     * Edit a user profile
     *
     * @param  UserProfile $profile
     * @param  string      $tagline
     * @param              $summary
     * @param  string      $rate
     * @param              $travelRadius
     * @param              $bio
     * @param              $shortBio
     * @param              $videoStatus
     *
     * @return UserProfile
     */
    public static function edit(
        UserProfile $profile,
        $tagline,
        $summary,
        $rate,
        $travelRadius,
        $bio,
        $shortBio,
        $videoStatus
    ) {
        // Attributes
        $profile->tagline       = $tagline ?: $profile->tagline;
        $profile->summary       = $summary ?: $profile->summary;
        $profile->rate          = $rate ?: $profile->rate;
        $profile->bio           = $bio ?: $profile->bio;
        $profile->short_bio     = $shortBio;
        $profile->video_status   = $videoStatus ?: $profile->video_status;
        $profile->travel_radius = isset($travelRadius)
            ? $travelRadius
            : $profile->travel_radius;
        // Event
        $profile->raise(new UserProfileWasEdited($profile));

        // Return
        return $profile;
    }

    /**
     * Mark a user profile as submittable for review
     *
     * @param  UserProfile $profile
     *
     * @return UserProfile
     */
    public static function submittable(UserProfile $profile)
    {
        // Attributes
        $profile->status = static::SUBMITTABLE;

        // Return
        return $profile;
    }

    /**
     * Complete a user profile information
     *
     * @param  UserProfile $profile
     *
     * @return UserProfile
     */
    public static function complete(UserProfile $profile)
    {
        // Attributes
        $profile->required = UserRequirement::PROFILE_SUBMIT;
        // Raise
        $profile->raise(new UserProfileWasCompleted($profile));

        // Return
        return $profile;
    }

    /**
     * Submit a user profile for review
     *
     * @param  UserProfile $profile
     *
     * @return UserProfile
     */
    public static function submit(UserProfile $profile)
    {

        if ($profile->admin_status !== static::REJECTED) {
            // Attributes
            $profile->status       = static::PENDING;
            $profile->admin_status = static::REVIEW;
            $profile->required     = UserRequirement::PAYOUTS;
            // Raise
            $profile->raise(new UserProfileWasSubmitted($profile));
        }

        // Return
        return $profile;
    }

    /**
     * Mark a user profile as ok
     *
     * @param  UserProfile $profile
     *
     * @return UserProfile
     */
    public static function ok(UserProfile $profile)
    {
        // Attributes
        $profile->admin_status = static::OK;
        if ($profile->status !== static::OFFLINE) {
            $profile->status = static::LIVE;
            // Raise
            $profile->raise(new UserProfileWasMadeLive($profile));
            $profile->raise(new UserProfileWasAccepted($profile));
        }

        // Return
        return $profile;
    }

    /**
     * Mark a user profile as rejected
     *
     * @param  UserProfile $profile
     *
     * @return UserProfile
     */
    public static function rejected(UserProfile $profile)
    {
        // Attributes
        $profile->admin_status = static::REJECTED;
        // Raise
        $profile->raise(new UserProfileWasRejected($profile));

        // Return
        return $profile;
    }

    /**
     * Mark a user profile as expired
     *
     * @param  UserProfile $profile
     *
     * @return UserProfile
     */
    public static function expire(UserProfile $profile)
    {
        // Attributes
        $profile->status = static::EXPIRED;

        // Return
        return $profile;
    }

    /**
     * Set a profile to it's live state
     *
     * @param  UserProfile $profile
     *
     * @return UserProfile
     */
    public static function live(UserProfile $profile)
    {
        // Attributes
        $profile->status = static::LIVE;
        // Raise
        $profile->raise(new UserProfileWasMadeLive($profile));

        // Return
        return $profile;
    }

    /**
     * Set a profile to it's offline state
     *
     * @param  UserProfile $profile
     *
     * @return UserProfile
     */
    public static function offline(UserProfile $profile)
    {
        // Attributes
        $profile->status = static::OFFLINE;
        // Raise
        $profile->raise(new UserProfileWasMadeOffline($profile));

        // Return
        return $profile;
    }

    /**
     * Rate a given user profile.
     *
     * @param  UserProfile $profile
     * @param  float       $rating
     * @param  integer     $count
     */
    public static function rate(UserProfile $profile, $rating, $count)
    {
        // Attributes
        $profile->rating        = $rating;
        $profile->ratings_count = $count;

        // Return
        return $profile;
    }

    /**
     * A profile belongs to a tutor
     *
     * @return BelongsToMany
     */
    public function tutor()
    {
        return $this->belongsTo(
            'App\User',
            'user_id'
        );
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only tutors that are live
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLive($query)
    {
        return $query->where(
            'admin_status',
            static::OK
        )
            ->where(
                'status',
                static::LIVE
            );
    }

    /**
     * Scope a query to only tutors that are offline
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOffline($query)
    {
        return $query->where(
            'admin_status',
            static::OK
        )
            ->where(
                'status',
                static::OFFLINE
            );
    }

    /**
     * Scope a query to only tutors that are accepted
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAccepted($query)
    {
        return $query->where(
            'admin_status',
            static::OK
        );
    }

    /**
     * Scope a query to only tutors that are rejected
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where(
            'admin_status',
            static::REJECTED
        );
    }

    /**
     * Scope a query to only tutors who aren't rejected
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotRejected($query)
    {
        return $query->where(
            'admin_status',
            '!=',
            static::REJECTED
        );
    }

    /**
     * Mutate the rate value
     *
     * @param  String $value
     *
     * @return Integer|null
     */
    public function getRateAttribute($value)
    {
        return $value === null ? null : (int)$value;
    }

    /**
     * Get allowed range of rates that a tutor can set for a booking (with a trial check)
     *
     * @param int $trial
     * @return array
     */
    public function getAllowedRateRange($trial = 0)
    {
        return $trial ? [
            'min' => 5,
            'max' => $this->rate * 2
        ] : [
            'min' => $this->rate ? floor($this->rate * 0.5) : 0,
            'max' => ceil($this->rate * 2)
        ];
    }

    /**
     * Mutate the travel radius attribute.
     *
     * @param  String $value
     *
     * @return Integer|null
     */
    public function getTravelRadiusAttribute($value)
    {
        return $value === null ? null : (int)$value;
    }

    /**
     * Mutate the bio attribute.
     *
     * @param  String $value
     *
     * @return String
     */
    public function setBioAttribute($value)
    {
        $this->attributes['bio'] = htmlspecialchars($value);
    }

}

<?php namespace App;

use App\User;
use App\Image;
use Carbon\Carbon;
use App\Database\Model;
use App\Observers\BackgroundCheckObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use App\Events\BackgroundCheckWasCreated;
use App\Events\BackgroundAdminStatusWasMadePending;
use App\Events\BackgroundAdminStatusWasMadeApproved;
use App\Events\BackgroundAdminStatusWasMadeRejected;

class UserBackgroundCheck extends Model
{

    const TYPE_DBS_CHECK  = 1;
    const TYPE_DBS_UPDATE = 2;

    const ADMIN_STATUS_PENDING  = 1;
    const ADMIN_STATUS_APPROVED = 2;
    const ADMIN_STATUS_REJECTED = 3;

    const FILTER_PENDING = 'pending';
    const FILTER_EXPIRED = 'expired';

    const STATUS_EXPIRED = 'expired';
    const STATUS_LIVE    = 'live';

    const EXPIRATION_INTERVAL = 'P3Y';

    const DBS_REJECT_REASON_OUT_OF_DATE = 1;
    const DBS_REJECT_REASON_NO_COLOUR   = 2;
    const DBS_REJECT_REASON_NOT_CLEAR   = 3;
    const DBS_REJECT_REASON_NOT_WHOLE   = 4;
    const DBS_REJECT_REASON_CUSTOM      = 5;

    const DBS_UPDATE_REJECT_REASON_NOT_FOUND  = 1;
    const DBS_UPDATE_REJECT_REASON_SERVICE_ID = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dbs',
        'issued_at',
        'certificate_number',
        'legal_last_name',
        'dob',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'dbs' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'issued_at',
        'dob',
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        parent::observe(app(BackgroundCheckObserver::class));
    }

    /**
     * Each booking morphs to many reminders
     *
     * @return MorphMany
     */
    public function reminders()
    {
        return $this->morphMany('App\Reminder', 'remindable');
    }

    /**
     * Create a background check instance.
     *
     * @param  User     $user
     * @param  Image    $image
     * @param  String   $uuid
     * @param  Integer  $status
     *
     * @return UserBackgroundCheck
     */
    public static function makeDbs(
        User  $user,
        Image $image = null,
        $uuid,
        $status = null
    ) {
        // New
        $entity = new static();

        $entity->user()->associate($user);

        if($image) {
            $entity->image()->associate($image);
        }

        // Attributes
        $entity->type         = self::TYPE_DBS_CHECK;
        $entity->uuid         = $uuid;
        $entity->admin_status = $status ? $status : self::ADMIN_STATUS_PENDING;

        // Raise
        $entity->raise(new BackgroundCheckWasCreated($entity));

        // Return
        return $entity;
    }

    /**
     * Create a background check instance.
     *
     * @param  User     $user
     * @param  String   $uuid
     * @param  Integer  $type
     * @param  Integer  $status
     *
     * @return UserBackgroundCheck
     */
    public static function make(
        User  $user,
        $uuid,
        $type,
        $status = null
    ) {
        // New
        $entity = new static();

        $entity->user()->associate($user);

        // Attributes
        $entity->type         = $type;
        $entity->uuid         = $uuid;
        $entity->admin_status = $status ? $status : self::ADMIN_STATUS_PENDING;

        // Raise
        $entity->raise(new BackgroundCheckWasCreated($entity));

        // Return
        return $entity;
    }

    /**
     * Create a background check instance.
     *
     * @param  User     $user
     * @param  String   $uuid
     * @param  Integer  $status
     * @param  String   $certificateNumber
     * @param  String   $lastName
     * @param  Carbon   $dob
     *
     * @return UserBackgroundCheck
     */
    public static function makeDbsUpdate(
        User  $user,
        $uuid,
        $status = null,
        $certificateNumber = null,
        $lastName = null,
        Carbon $dob = null
    ) {
        // New
        $entity = new static();

        $entity->user()->associate($user);

        // Attributes
        $entity->type               = self::TYPE_DBS_UPDATE;
        $entity->uuid               = $uuid;
        $entity->certificate_number = $certificateNumber;
        $entity->last_name          = $lastName;
        $entity->dob                = $dob;
        $entity->admin_status       = $status ? $status : self::ADMIN_STATUS_PENDING;

        // Raise
        $entity->raise(new BackgroundCheckWasCreated($entity));

        // Return
        return $entity;
    }

    /**
     * Set a background admin status to pending
     *
     * @param  UserBackgroundCheck $background
     *
     * @return UserBackgroundCheck
     */
    public static function pending(UserBackgroundCheck $background)
    {
        $previousStatus = $background->admin_status;

        // Attributes
        $background->admin_status = self::ADMIN_STATUS_PENDING;


        if($previousStatus !== $background->admin_status) {
            // Raise
            $background->raise(new BackgroundAdminStatusWasMadePending($background));
        }

        // Return
        return $background;
    }

    /**
     * Set a background admin status to approved
     *
     * @param  UserBackgroundCheck $background
     *
     * @return UserBackgroundCheck
     */
    public static function approve(UserBackgroundCheck $background)
    {
        $previousStatus = $background->admin_status;

        // Attributes
        $background->admin_status = self::ADMIN_STATUS_APPROVED;

        if($previousStatus !== $background->admin_status) {
            // Raise
            $background->raise(new BackgroundAdminStatusWasMadeApproved($background));
        }

        // Return
        return $background;
    }

    /**
     * Set a background admin status to rejected
     *
     * @param  UserBackgroundCheck $background
     *
     * @return UserBackgroundCheck
     */
    public static function reject(UserBackgroundCheck $background)
    {
        $previousStatus = $background->admin_status;

        // Attributes
        $background->admin_status = self::ADMIN_STATUS_REJECTED;

        if($previousStatus !== $background->admin_status) {
            // Raise
            $background->raise(new BackgroundAdminStatusWasMadeRejected($background));
        }

        // Return
        return $background;
    }

    /**
     * Check if background expired
     *
     * @param  UserBackgroundCheck $background
     *
     * @return boolean
     */
    public static function isExpired(UserBackgroundCheck $background)
    {
        $expired = true;

        $issuedAt       = $background->issued_at;
        $expirationDate = self::getExpiredDate();

        if($issuedAt && $issuedAt > $expirationDate) {
            $expired = false;
        }

        // Return
        return $expired;
    }

    /**
     * Get date of expiration from current date
     *
     * @return \DateTime
     */
    public static function getExpiredDate()
    {
        $expiredDate = new \DateTime();
        $expiredDate->sub(new \DateInterval(self::EXPIRATION_INTERVAL));

        return $expiredDate;
    }

    /**
     * The background check belong to a user.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The background check belong to an image.
     */
    public function image()
    {
        return $this->belongsTo('App\Image');
    }

    /**
     * @param Builder $query
     * @param \App\User $user
     *
     * @return Builder|static
     */
    public function scopeHasUser(Builder $query, User $user)
    {
        return $query->whereHas('user', function ($q) use ($user) {
            return $q->where('id', '=', $user->id);
        });
    }

    /**
     * Mutate the dbs attribute.
     *
     * @return Boolean
     */
    public function getDbsAttribute($value)
    {
        return (bool) $value;
    }

}

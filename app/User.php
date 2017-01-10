<?php namespace App;

use App\Role;
use App\Address;
use Carbon\Carbon;
use App\Database\Model;
use App\Events\UserWasEdited;
use App\Observers\UserObserver;
use App\Events\UserWasRegistered;
use App\Events\UserWasBlocked;
use App\Events\UserWasLegalEdited;
use App\Events\UserCardWasUpdated;
use Illuminate\Auth\Authenticatable;
use App\Billing\Contracts\BankInterface;
use App\Billing\Contracts\CardInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Billing\Contracts\UserAccountInterface;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;

class User extends Model implements
    AuthenticatableContract,
    CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'dob',
        'email',
        'telephone',
        'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'confirmation_token',
        'created_at',
        'updated_at'
    ];

    /**
     * Request laravel transform these attributes into
     * Carbon instances
     *
     * @var array
     */
    protected $dates = [
        'dob',
        'deleted_at'
    ];

    /**
     * Create a new Collection instance.
     *
     * @param  array  $models
     * @return Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collection(array_map(function ($model) {
            return static::cast($model);
        }, $models));
    }

    /**
     * Register a new user.
     *
     * @param string uuid
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $telephone
     * @param string $password
     * @return User
     */
    public static function register(
        $uuid,
        $firstName,
        $lastName,
        $email,
        $telephone,
        $password
    ) {
        $user = new static();

        $user->uuid               = $uuid;
        $user->first_name         = $firstName;
        $user->last_name          = $lastName;
        $user->email              = $email;
        $user->telephone          = $telephone;
        $user->password           = $password;
        $user->confirmation_token = str_uuid();

        $user->raise(new UserWasRegistered($user));

        return $user;
    }

    /**
     * Confirm a registration
     *
     * @param  User $user
     * @return User
     *
     * @todo change the method name to 'confirm'
     */
    public static function confirmRegistration(User $user)
    {
        $user->confirmed = true;
        // raise
        return $user;
    }


    /**
     * Edit a user.
     *
     * @param  User $user
     * @param  array $attributes
     * @return User
     */
    public static function edit(
        User  $user,
        $firstName,
        $lastName,
        $email,
        $telephone,
        $password
    ) {
        // Attributes
        $user->first_name = $firstName ?: $user->first_name;
        $user->last_name  = $lastName  ?: $user->last_name;
        $user->email      = $email     ?: $user->email;
        $user->telephone  = $telephone ?: $user->telephone;
        // Password
        if ($password) {
            $user->password = $password;
        }
        // Event
        $user->raise(new UserWasEdited($user));
        // Return
        return $user;
    }

    
    public static function legal(
        User $user,
        $firstName,
        $lastName,
        Carbon $dob
    ) {
        // Attributes
        $user->legal_first_name = $firstName;
        $user->legal_last_name  = $lastName;
        $user->dob              = $dob;
        // Event
        $user->raise(new UserWasLegalEdited($user));
        // Return
        return $user;
    }

    /**
     * Update a users billing
     *
     * @param  User                 $user
     * @param  UserAccountInterface $account
     * @return User
     */
    public static function billing(
        User                 $user,
        UserAccountInterface $account
    ) {
        $user->billing_id = $account->id;
        // raise
        return $user;
    }

    /**
     * Update a users bank details
     *
     * @param  User          $user
     * @param  BankInterface $bank
     * @return User
     */
    public static function bank(User $user, BankInterface $bank)
    {
        $user->last_four = $bank->last_four;
        // raise
        return $user;
    }

    /**
     * Update a users card details
     *
     * @param  User          $user
     * @param  CardInterface $card
     * @return User
     */
    public static function card(User $user, CardInterface $card)
    {
        // Attributes
        $user->last_four = $card->last_four;
        // Event
        $user->raise(new UserCardWasUpdated($user));
        // Return
        return $user;
    }

    /**
     * Update user password
     *
     * @param  User   $user
     * @param  string $password
     *
     * @todo change the method name, or just used static::edit
     */
    public static function changePassword(User $user, $password)
    {
        $user->password = $password;
        // raise
        return $user;
    }

    /**
     * Changes the user blocked status
     *
     * @param $user
     * @return mixed
     */
    public static function toggleBlocked(User $user) {

        if (empty($user->blocked_at)) {
            $user->blocked_at = Carbon::now();
            $user->raise(new UserWasBlocked($user));

        } else {
            $user->blocked_at = null;
        }

        return $user;
    }

    /**
     * Re-cast the User object into either a Tutor or Student object depending
     * on the users roles.
     *
     * @param  User $old
     * @return Tutor|Student
     */
    public static function cast(User $old)
    {
        if ($old->roles->contains('name', Role::ADMIN)) {
            $new = new Admin();
        } elseif ($old->roles->contains('name', Role::TUTOR)) {
            $new = new Tutor();
        } else {
            $new = new Student();
        }

        $new->setRawAttributes($old->getAttributes(), true);
        $new->setRelations($old->getRelations());
        $new->syncOriginal();

        $new->exists = $old->exists;

        return $new;
    }

    /**
     * The user belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id')
            ->withTimestamps();
    }

    /**
     * The user has many relationships
     */
    public function relationships()
    {
        return $this->hasMany('App\Relationship');
    }

    /**
     * The user has many applications
     */
    public function applications()
    {
        return $this->hasMany('App\Relationship')
            ->where('is_application', '=', true);
    }

    /**
     * The user has many enquiries
     */
    public function enquiries()
    {
        return $this->hasMany('App\Relationship')
            ->where('is_application', '=', false);
    }

    /**
     * The user belongs to many addresses.
     *
     * @return BelongsToMany
     */
    public function addresses()
    {
        return $this->belongsToMany('App\Address', 'address_user', 'user_id')
            ->withPivot(['name'])
            ->withTimestamps();
    }

    /**
     * The user has one  identity document
     *
     * @return HasOne
     */
    public function identityDocument()
    {
        return $this->hasOne('App\IdentityDocument', 'user_id');
    }

    /**
     * The user belongs to many messages.
     *
     * @return BelongsToMany
     */
    public function messages()
    {
        return $this->hasManyThrough(
            'App\Message',
            'App\Relationship'
        );
    }

    /**
     * The user has many tasks.
     *
     * @return MorphMany
     */
    public function tasks()
    {
        return $this->morphToMany('App\Task', 'taskable')->orderBy('action_at');
    }

    /**
     * The user has many transgressions.
     *
     * @return MorphMany
     */
    public function transgressions()
    {
        return $this->hasMany('App\Transgression', 'user_id');
    }


    /** The user has many reminders.
     *
     * @return MorphMany
     */
    public function reminders()
    {
        return $this->morphMany('App\Reminder', 'remindable');
    }
    /**
     * A user has one note
     *
     * @return MorphOne
     */
    public function note()
    {
        return $this->morphOne('App\Note', 'noteable');
    }

    /**
     * A user has many notification schedules
     *
     * @return MorphOne
     */
    public function schedules()
    {
        return $this->hasMany('App\Schedules\NotificationSchedule', 'user_id');
    }


    /**
     * A user has one subscription
     *
     * @return HasOne
     */
    public function subscription()
    {
        return $this->hasOne('App\UserSubscription', 'user_id');
    }

    /**
     * Is the user an admin?
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->roles->contains('name', Role::ADMIN);
    }

    /**
     * Is the user a tutor?
     *
     * @return boolean
     */
    public function isTutor()
    {
        return $this->roles->contains('name', Role::TUTOR);
    }

    /**
     * Is the user a student?
     *
     * @return boolean
     */
    public function isStudent()
    {
        return $this->roles->contains('name', Role::STUDENT);
    }

    /**
     * Mutate the confirmed attribute on get.
     *
     * @param  String $value
     * @return Boolean
     */
    public function getConfirmedAttribute($value)
    {
        return (boolean) $value;
    }

    /**
     * Mutate the password property on set.
     *
     * @param  mixed $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * The user has many articles
     */
    public function articles()
    {
        return $this->hasMany('Articles');
    }

    public function referral_data()
    {
        return $this->hasOne('App\ReferralUsers', 'user_id');
    }


    // Scopes /////////////////////////////////////////////////////////////////////////////////

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeViewable(Builder $query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeHasConfirmedRelationship($query)
    {
        return $query->whereHas('relationships', function($q) {
            $q->where('is_confirmed', 1);
        });
    }

}

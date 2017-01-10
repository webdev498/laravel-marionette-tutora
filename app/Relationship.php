<?php

namespace App;

use App\Database\Model;
use App\Events\RelationshipWasMade;
use App\Events\RelationshipWasMismatched;
use App\Events\StudentWasMatched;
use App\Observers\RelationshipObserver;
use Carbon\Carbon;

/**
 * A relationship models the relationship between a tutor and a student. 
 */

class Relationship extends Model
{
    const REQUESTED_BY_TUTOR = 'requested_by_tutor';
    const PENDING            = 'pending';  // initially set at this
    const CONFIRMED          = 'confirmed';
    const CHATTING           = 'chatting';
    const MISMATCHED         = 'mismatched';
    const NO_REPLY           = 'no_reply';
    const NO_REPLY_STUDENT   = 'no_reply_student';


    /**
     * Request laravel transform these attributes into
     * Carbon instances
     *
     * @var array
     */
    protected $dates = [
        'created_at'
    ];

    /**
     * The database table for this model
     */
    protected $table = 'relationships';

    /**
     * Boot the model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        parent::observe(app(RelationshipObserver::class));
    }

    /**
     * Make a relationship.
     *
     * @param  Tutor   $tutor
     * @param  Student $student
     * @return Relationship
     */
    public static function make(Tutor $tutor, Student $student)
    {
        // New
        $relationship = new static();

        // Attributes
        $relationship->tutor_id   = $tutor->id;
        $relationship->student_id = $student->id;
        $relationship->is_confirmed = 0;
        $relationship->status = static::PENDING;
        // Raise
        $relationship->raise(new RelationshipWasMade($relationship));
        // Return
        return $relationship;
    }

    /**
     * Confirm a relationship.
     *
     * @param  Relationship $relationship
     * @return Relationship
     */
    public static function confirm(Relationship $relationship)
    {
        // Attributes
        $relationship->is_confirmed = 1;
        $relationship->reason = null;
        $relationship->status = static::CONFIRMED;
        $relationship->confirmed_at = Carbon::now();
        // Return
        return $relationship;
    }
    /**
     * Is a relationship confirmed
     *
     * @param  Relationship $relationship
     * @return boolean
     */
    public function isConfirmed()
    {
        return $this->is_confirmed;
    }

    /**
     * A relationship has one tutor
     *
     * @return HasOne
     */
    public function tutor()
    {
        return $this->hasOne('App\User', 'id', 'tutor_id');
    }

    /**
     * A relationship has one student
     *
     * @return HasOne
     */
    public function student()
    {
        return $this->hasOne('App\User', 'id', 'student_id');
    }

    /**
     * A relationship has one message
     *
     * @return HasOne
     */
    public function message()
    {
        return $this->hasOne('App\Message', 'relationship_id')
            ->orderBy('updated_at', 'desc');
    }

    /**
     * A relationship has many lessons
     *
     * @return HasMany
     */
    public function lessons()
    {
        return $this->hasMany('App\Lesson', 'relationship_id');
    }

    /**
     * A relationship has many bookings
     *
     * @return BelongsToMany
     */
    public function bookings()
    {
        return $this->hasManyThrough(
            'App\LessonBooking',
            'App\Lesson',
            'relationship_id',
            'lesson_id'
        );
    }

    /**
     * A relationship has many tasks
     *
     * @return MorphMany
     */
    public function tasks()
    {
        return $this->morphToMany('App\Task', 'taskable');
    }

    /**
     * A relationship has one note
     *
     * @return MorphOne
     */
    public function note()
    {
        return $this->morphOne('App\Note', 'noteable');
    }

     /**
     * A relationship has many searches
     *
     * @return MorphMany
     */   

    public function searches()
    {
        return $this->morphToMany('App\Search', 'searchable');
    }

    /**
     * @param $rate
     */
    public function setHourlyRate($rate)
    {
        $this->rate = $rate;
        $this->save();
    }

    /**
     * @param $intent
     * @param null $reason
     */
    public function setTutorIntentToHelp($intent, $reason = null)
    {

        if ($intent) {
            $this->reason = null;
            $this->status = static::CHATTING;
            $this->save();
            event(new StudentWasMatched($this->student));
        } else {
            $this->reason = $reason;
            $this->status = static::MISMATCHED;
            $this->save();
            event(new RelationshipWasMismatched($this));
        }

    }

    public function newFromBuilder($attributes = [], $connection = null)
    {
        $class = $attributes->is_application ? JobApplication::class : Enquiry::class;
        $model = new $class;
        $model->exists = true;
        $model->setRawAttributes((array) $attributes, true);

        $model->setConnection($connection ?: $this->connection);

        return $model;
    }  

    // Scopes //////////////////////////////////////

    public function scopeRecent($query)
    {
        return $query->where('created_at', '>', Carbon::now()->subDays(config('relationships.recent_period')));
    }
}

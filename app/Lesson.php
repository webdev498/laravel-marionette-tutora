<?php

namespace App;

use App\LessonBooking;
use App\Database\Model;
use App\Events\LessonWasBooked;
use App\Events\LessonWasEdited;
use App\Events\LessonWasCancelled;
use App\Events\LessonWasConfirmed;

/**
 * A Lesson is the parent of a series of bookings.
 *
 * It holds the information that never changes within series of bookings (i.e.
 * Tutor, Student, Subject), as well as changes that will be carried forth
 * to newly scheduled lessons.
 *
 * It also has a schedule
 */
class Lesson extends Model
{
    const PENDING   = 'pending';
    const CONFIRMED = 'confirmed';

    // lesson types
    const REGULAR = 'regular';
    const TRIAL = 'trial';

    /**
     * Request laravel transform these attributes into
     * Carbon instances
     *
     * @var array
     */
    protected $dates = [
        'start_at'
    ];

    /**
     * Create a lesson instance.
     *
     * @param  Relationhsip $relationship
     * @param  Subject      $subject
     * @param  Integer      $duration in seconds.
     * @param  Integer      $rate     in pounds.
     * @param  String       $duration
     * @return Lesson
     */
    public static function make(
        Relationship $relationship,
        Subject      $subject,
        $duration,
        $rate,
        $location,
        $trial
    ) {
        // New
        $lesson = new static();
        // Relationships
        $lesson->relationship()->associate($relationship);
        $lesson->subject()->associate($subject);
        // Attributes
        $lesson->duration = $duration;
        $lesson->rate     = $rate;
        $lesson->location = $location;
        $lesson->status   = $relationship->is_confirmed ? static::CONFIRMED : static::PENDING;
        $lesson->type = $trial ? static::TRIAL : static::REGULAR;
        // Raise
        $lesson->raise(new LessonWasBooked($lesson));
        // Return
        return $lesson;
    }

    /**
     * Edit a lesson instance.
     *
     * @param  Lesson $lesson
     * @param  String  $location
     * @return Lesson
     */
    public static function edit(
        Lesson $lesson,
        $duration,
        $rate,
        $location
    ) {
        // Attributes
        $lesson->location = $location;
        $lesson->duration = $duration;
        $lesson->rate = $rate;
        // Raise
        $lesson->raise(new LessonWasEdited($lesson));
        // Return
        return $lesson;
    }

    /**
     * Confirm a given lesson.
     *
     * @param  Lesson $lesson
     * @return Lesson
     */
    public static function confirm(Lesson $lesson)
    {
        // Attributes
        $lesson->status = static::CONFIRMED;
        // Raise
        $lesson->raise(new LessonWasConfirmed($lesson));
        // Return
        return $lesson;
    }

    /**
     * Each lesson belongs to one relationship
     *
     * @return BelongsTo
     */
    public function relationship()
    {
        return $this->belongsTo('App\Relationship');
    }

    /**
     * Each lesson has one subject
     *
     * @return BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo('App\Subject');
    }

    /**
     * Each lesson has multiple bookings.
     *
     * @return HasMany
     */
    public function bookings()
    {
        return $this->hasMany('App\LessonBooking');
    }

    /**
     * Each lesson has one schedule.
     *
     * @return HasOne
     */
    public function schedule()
    {
        return $this->hasOne('App\LessonSchedule');
    }

    public function isConfirmed()
    {
        return $this->status === static::CONFIRMED;
    }

}


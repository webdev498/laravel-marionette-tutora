<?php

namespace App;

use App\Database\Model;
use App\TaskCollection;

class Task extends Model
{
    
    const GENERAL    = 'general';

    // Student Task Categories
    const FAILED_PAYMENT    = 'failed_payment';
    const EXPIRED_LESSON    = 'expired_lesson';
    const PENDING_LESSON    = 'pending_lesson';
    const NOT_REPLIED       = 'not_replied';
    const MISMATCHED_NO_JOB = 'mismatched_no_job';
    const MISMATCHED_HAS_JOB = 'mismatched_has_job';

    // Tutor Task Categories
    const CANCELLED_FIRST_LESSON= 'cancelled_first_lesson';
    const REBOOK                = 'rebook';
    const FIRST_LESSON_NO_REBOOK = 'first_lesson_no_rebook';
    const FIRST_LESSON_REBOOK   = 'first_lesson_rebook';
    const FIRST_WITH_STUDENT_NO_REBOOK    = 'first_with_student_no_rebook';
    const FIRST_WITH_STUDENT_REBOOK = 'first_with_student_rebook';
    const REFUND = 'refund';
    const DISINTERMEDIATING = 'disintermediating';
    const LESSON_COUNT          = 'lesson_count';
    const TRANSGRESSION         = 'transgression';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'action_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'action_at',
    ];

    /**
     * A task belongs to many users
     *
     * @return BelongsTo
     */
    public function users()
    {
        return $this->morphedByMany('App\User', 'taskable')
            ->withTimestamps();
    }

    /**
     * A task belongs to many relationships
     *
     * @return BelongsTo
     */
    public function relationships()
    {
        return $this->morphedByMany('App\Relationship', 'taskable')
            ->withTimestamps();
    }

    /**
     * Many things can be taskable
     *
     * @return MorphTo
     */
    public function taskable()
    {
        return $this->morphTo();
    }
}

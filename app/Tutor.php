<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tutor extends User
{
 
    /**
     * Override the parent newQuery class to only show those with roles of Tutor
     * 
     * @return Builder $query
     */
    public function newQuery()
    {
        $query = parent::newQuery();
        $query->has('roles', 1)
            ->whereHas('roles', function ($q) {
                return $q->where('name', '=', Role::TUTOR);
            });

        return $query;
    }


    /**
     * A tutor has a relationship with many students
     */
    public function students()
    {
        return $this->hasManyThrough(
            'App\User',
            'App\Relationship',
            'tutor_id',
            'id',
            null,
            'student_id'
        )
            ->whereNull('deleted_at')
            ->where('relationships.status', '!=', Relationship::REQUESTED_BY_TUTOR);
    }

    /**
     * The tutor has many lessons.
     *
     * @return HasMany
     */
    public function lessons()
    {
        return $this->hasManyThrough('App\Lesson', 'App\Relationship');
    }

    /**
     * The user has one profile.
     *
     * @return HasOne
     */
    public function profile()
    {
        return $this->hasOne('App\UserProfile', 'user_id');
    }

    /**
     * The tutor has many requirements
     *
     * @return HasMany
     */
    public function requirements()
    {
        return $this->hasMany('App\UserRequirement', 'user_id')
            ->whereIn('for', [
                $this->profile->required,
                UserRequirement::PROFILE_SUBMIT,
                UserRequirement::ANY
            ]);
    }

    /**
     * A tutor may have many reviews
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany('App\UserReview', 'user_id');
    }

    /**
     * The user belongs to many subjects.
     *
     * @return BelongsToMany
     */
    public function subjects()
    {
        return $this->belongsToMany('App\Subject', 'subject_user', 'user_id')
            ->with('parent')
            ->orderBy('id')
            ->withTimestamps();
    }

    /**
     * The Tutor belongs to many jobs.
     *
     * @return BelongsToMany
     */
    public function jobs()
    {
        return $this->belongsToMany('App\Job', 'tuition_job_tutor', 'user_id')
            ->withPivot(['favourite', 'applied'])
            ->withTimestamps();
    }

    /**
     * The user has many university qualifications.
     *
     * @return HasMany
     */
    public function qualificationUniversities()
    {
        return $this->hasMany('App\UserQualificationUniversity', 'user_id');
    }

    /**
     * The user has many a-level qualifications.
     *
     * @return HasMany
     */
    public function qualificationAlevels()
    {
        return $this->hasMany('App\UserQualificationAlevel', 'user_id');
    }

    /**
     * The user has many other qualifications.
     *
     * @return HasMany
     */
    public function qualificationOthers()
    {
        return $this->hasMany('App\UserQualificationOther', 'user_id');
    }

    /**
     * The user has one teacher status qualification.
     *
     * @return HasOne
     */
    public function qualificationTeacherStatus()
    {
        return $this->hasOne('App\UserQualificationTeacherStatus', 'user_id');
    }

    /**
     * The user has many background checks.
     *
     * @return hasMany
     */
    public function backgroundCheck()
    {
        return $this->hasMany('App\UserBackgroundCheck', 'user_id');
    }

    /**
     * The user has one background check.
     *
     * @param Integer $type
     *
     * @return hasMany
     */
    public function backgroundCheckWithType($type)
    {
        return $this->backgroundCheck()
            ->where('type', '=', $type);
    }

    /**
     * The user has one background check.
     *
     * @return hasMany
     */
    public function actualBackgroundCheck()
    {
        $actualIssuedTime = strtotime("-2 years", time());

        return $this->backgroundCheck()
            ->where('admin_status', '=', UserBackgroundCheck::ADMIN_STATUS_APPROVED)
            ->where('issued_at', '>=', $actualIssuedTime);
    }

    /**
     * The user has one experience.
     *
     * @return HasOne
     */
    public function experience()
    {
        return $this->hasOne('App\UserExperience', 'user_id');
    }

    /**
     * The user has one signup schedule.
     *
     * @return HasOne
     */
    public function signupSchedule()
    {
        return $this->hasOne('App\Schedules\TutorSignupSchedule', 'user_id');
    }

    /**
     * The student has one job schedule.
     *
     * @return HasOne
     */
    public function jobsSchedule()
    {
        return $this->hasOne('App\Schedules\TutorJobsSchedule', 'user_id');
    }

    /**
     * @param Tutor $tutor
     * @param User $actioner
     * @return Tutor
     */
    public static function trash(Tutor $tutor)
    {
        $tutor->deleted_at = Carbon::now();
        $tutor->email = $tutor->email . '-deleted';
        $tutor->last_four = null;

        return $tutor;
    }

    /////////////////////////////////////////////////////////////////////////////////

    public function scopeLive($query)
    {
        return $query->whereHas('profile', function ($q) {
            $q->live();
        });
    }

    public function scopeOffline($query)
    {
        return $query->whereHas('profile', function ($q) {
            $q->offline();
        });
    }

    public function scopeAccepted($query)
    {
        return $query->whereHas('profile', function ($q) {
            $q->accepted();
        });
    }

    public function scopeNotRejected($query)
    {
        return $query->whereHas('profile', function ($q) {
            $q->notRejected();
        });
    }

    public function scopeRejected($query)
    {
        return $query->whereHas('profile', function ($q) {
            $q->rejected();
        });
    }


}

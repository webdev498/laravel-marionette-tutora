<?php namespace App;

use App\Database\Scopes\TakePageTrait;
use App\Role;
use App\Tasks\StudentTasksTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends User
{
    use TakePageTrait, StudentTasksTrait;

    /**
     * Override the parent newQuery class to only show those with roles of student
     * 
     * @return Builder $query
     */
    public function newQuery()
    {
        $query = parent::newQuery();
        $query->has('roles', 1)
            ->whereHas('roles', function ($q) {
                return $q->where('name', '=', Role::STUDENT);
            });

        return $query;
    }


    /**
     * The student has many lessons.
     *
     * @return HasMany
     */
    public function lessons()
    {
        return $this->hasManyThrough(
            'App\Lesson',
            'App\Relationship'
        );
    }

    /**
     * A student has many jobs
     *
     * @return HasMany
     */
    public function jobs()
    {
        return $this->hasMany('App\Job', 'user_id');
    }

    /**
     * The student has a relationship with many tutors.
     *
     * @return HasManyThrough
     */
    public function tutors()
    {
        return $this->hasManyThrough(
            'App\User',
            'App\Relationship',
            'student_id',
            'id',
            null,
            'tutor_id'
        );
    }

    /**
     * The student has one settings.
     *
     * @return HasOne
     */
    public function settings()
    {
        return $this->hasOne('App\StudentSetting', 'user_id');
    }

    /**
     * The student has many searches.
     *
     * @return HasMany
     */
    public function searches()
    {
        return $this->morphToMany('App\Search', 'searchable');
    }

    /**
     * The student has one marketing schedule.
     *
     * @return HasOne
     */
    public function marketingSchedule()
    {
        return $this->hasOne('App\Schedules\StudentMarketingSchedule', 'user_id');
    }

    /**
     * @param Student $student
     * @return Tutor
     */
    public static function trash(Student $student)
    {
        $student->deleted_at = Carbon::now();
        $student->email = $student->email . '-deleted';

        return $student;
    }

    // Scopes /////////////////////////////////////////////////////////////////////////////////////


}

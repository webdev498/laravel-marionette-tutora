<?php

namespace App\Repositories;

use DateTime;
use App\Role;
use App\Tutor;
use App\Student;
use App\LessonBooking;
use App\Relationship;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Traits\WithTasksTrait;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Traits\WithRelationshipsLessonsTrait;
use App\Repositories\Contracts\StudentRepositoryInterface;

class EloquentStudentRepository extends AbstractEloquentRepository
    implements StudentRepositoryInterface
{

    use WithRelationshipsLessonsTrait;
    use WithTasksTrait {
        withTasksNext as traitWithTasksNext;
    }

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Student
     */
    protected $student;

    /**
     * Create an instance of this repository.
     *
     * @param  Database $database
     * @param  Student  $student
     * @return void
     */
    public function __construct(
        Database $database,
        Student  $student
    ) {
        $this->database = $database;
        $this->student  = $student;
    }

    public function all()
    {
        return $this->student->all();
    }

    /**
     * Find a student by a given uuid
     *
     * @param  string $uuid
     * @return User|null
     */
    public function findByUuid($uuid)
    {
        return $this->student
            ->where('uuid', '=', $uuid)
            ->with($this->with)
            ->first();
    }

    /**
     * Get the students
     *
     * @return Collection
     */
    public function getByTask()
    {
        return $this->student
            ->select('users.*')
            ->viewable()
            ->addSelect($this->database->raw("(
                select `action_at`
                from `tasks`
                join `taskables` on `taskables`.`task_id` = `tasks`.`id`
                where `taskables`.`taskable_id` = `users`.`id`
                and `taskables`.`taskable_type` = 'App\\\Student'
                order by `action_at` asc
                limit 1
            ) as `action_at`"))
            ->with($this->with)
            ->has('tasks')
            ->takePage($this->page, $this->perPage)
            ->orderBy('action_at')
            ->orderBy('users.created_at')
            ->get();
    }

    /**
     * Count the number of students
     *
     * @return integer
     */
    public function count()
    {
        return $this->student->count();
    }

    /**
     * Get the students by a given task category
     *
     * @param  string $status
     * @return Collection
     */
    public function getByTaskCategory($category)
    {
        return $this->student
            ->select('users.*')
            ->addSelect($this->database->raw("(
                select `action_at`
                from `tasks`
                join `taskables` on `taskables`.`task_id` = `tasks`.`id`
                where `taskables`.`taskable_id` = `users`.`id`
                and `taskables`.`taskable_type` = 'App\\\Student'
                and `tasks`.`category` = '$category'
                order by `action_at` asc
                limit 1
            ) as `action_at`"))
            ->with($this->with)
            ->whereHas('tasks', function ($q) use ($category) {
                $q->where('category', '=', $category);
            })
            ->orderBy('action_at')
            ->orderBy('users.created_at')
            ->takePage($this->page, $this->perPage)
            ->get();
    }


    /**
     * Count the students who have a given task category
     *
     * @param  string $status
     * @return Integer
     */
    public function countByTaskCategory($category)
    {
        return $this->student
            ->whereHas('tasks', function ($q) use ($category) {
                $q->where('category', '=', $category);
            })
            ->count();
    }

    /**
     * Get the students by searching the given columns for a given query.
     *
     * @param   string $query
     * @param   $columns
     * @return  Collection
     */
    public function getByQuery($term, $columns = [])
    {
        return $this->student
            ->where(function($query) use ($term, $columns) {
                foreach ($columns as $column) {
                    $query = $query->orWhere($column, 'LIKE', '%'.$term.'%');
                }
            })
            ->takePage($this->page, $this->perPage)
            ->get();
    }


    /**
     * Get a page of students
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getPage($page, $perPage)
    {
        // Return
        return $this->student
            ->with($this->with)
            ->takePage($page, $perPage)
            ->get();
    }

    /**
     * Get the students belonging to a tutor
     *
     * @param  Tutor   $tutor
     * @param  Integer $page
     * @param  Integer $perPage
     * @return Collection
     */
    public function getByTutor(Tutor $tutor, $page, $perPage)
    {
        $query = $tutor
            ->relationships()
            ->has('student')
            ->with('student')
            ->takePage($page, $perPage)
            ->orderBy('created_at', 'desc');

        $query->where('relationships.status', '!=', Relationship::REQUESTED_BY_TUTOR);

        $students = $query->lists('student_id');

        return $this->student
            ->with(array_extend([
                'relationships' => function ($query) use ($tutor) {
                    return $query->where('tutor_id', '=', $tutor->id);
                }
            ], $this->with))
            ->select('users.*')
            ->join('relationships', 'relationships.student_id', '=', 'users.id')
            ->viewable()
            ->where('relationships.tutor_id', '=', $tutor->id)
            ->orderBy('relationships.created_at', 'desc')
            ->whereIn('users.id', $students->toArray())
            ->get();
    }

    /**
     * Count the number of students that belong to a tutor
     *
     * @param  Tutor $tutor
     * @return integer
     */
    public function countByTutor(Tutor $tutor)
    {
        $query = $tutor
            ->relationships()
            ->whereHas('student', function($query) {
                $query->whereNull('deleted_at');
            });

        $query->where('relationships.status', '!=', Relationship::REQUESTED_BY_TUTOR);

        return $query->count();
    }



    /**
     * Select only the next task due.
     *
     * @param  MorphMany $query
     * @param  string    $taskableType = taskable_type
     * @return MorphMany
     */
    protected function withTasksNext($query)
    {
        return $this->traitWithTasksNext($query, Student::class);
    }

    // Analytics ////////////////////////////////////////////////////////////////

    /**
     * Count students between dates. Used by analytics
     *
     * @var start
     * @var end
     * @return integer
     */
    public function countBetweenDates(DateTime $start, DateTime $end)
    {
        $query = $this->student
            ->newQuery()
            ->createdBetween($start, $end);

        return $query->count();
    }


   /**
     * Count students between dates who have at least one confirmed relationship, where relationships was created_at. Used by analytics
     *
     * @var start
     * @var end
     * @return integer
     */
    public function countByConfirmedRelationshipsBetweenDates(DateTime $start, DateTime $end)
    {
        $query = $this->student
            ->hasConfirmedRelationship()
            ->createdBetween($start, $end);

        return $query->count();
    }


    public function countReactivatedStudentsAfterPeriodBetweenDates($period, DateTime $start, DateTime $end)
    {

        return $this->student
            ->whereIn('id', function($q) use ($start, $end, $period) {
                $q->select('users.id')
                ->from('relationships')
                ->join('users', 'users.id', '=', 'relationships.student_id')
                ->whereRaw("`users`.`created_at` < (`relationships`.`created_at` - INTERVAL $period) ")
                ->whereBetween('relationships.created_at', [$start, $end]);
            })->count();
    }
}

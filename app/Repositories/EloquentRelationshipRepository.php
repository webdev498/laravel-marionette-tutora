<?php

namespace App\Repositories;

use DateTime;
use App\Tutor;
use App\Student;
use App\User;
use App\Relationship;
Use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Traits\WithTasksTrait;
use App\Repositories\Traits\WithMessageLinesTrait;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\RelationshipRepositoryInterface;
use DB;

class EloquentRelationshipRepository extends AbstractEloquentRepository
    implements RelationshipRepositoryInterface
{
    use WithMessageLinesTrait {
        withLinesLast as withMessageLinesLast;
    }
    use WithTasksTrait {
        withTasksNext as traitWithTasksNext;
    }

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Relationship
     */
    protected $relationship;

    /**
     * Create an instance of the repository.
     *
     * @param  Database     $database
     * @param  Relationship $relationship
     */
    public function __construct(
        Database     $database,
        Relationship $relationship
    ) {
        $this->database     = $database;
        $this->relationship = $relationship;
    }

    /**
     * Save the relationship
     *
     * @param  Relationship $relationship
     * @return Relationship
     */
    public function save(Relationship $relationship)
    {
        // Save
        if ( ! $relationship->push()) {
            throw new ResourceNotPersisted();
        }
        // Return
        return $relationship;
    }

    /**
     * Find a relationship by a given id
     *
     * @param  integer $id
     * @return Relationship|null
     */
    public function findById($id)
    {
        // Return
        return $this->relationship
            ->newQuery()
            ->with($this->with)
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Find a relationship by a given id, or fail.
     *
     * @param  integer $id
     * @return Relationship
     * @throws ModelNotFoundException
     */
    public function findByIdOrFail($id)
    {
        return $this->orFail($this->findById($id));
    }

    /**
     * Get relationships
     *
     * @return Collection
     */
    public function get()
    {
        return $this->relationship
            ->newQuery()
            ->with($this->with)
            ->takePage($this->page, $this->perPage)
            ->get();
    }



    /**
     * Count relationships
     *
     * @param  string $status
     * @return integer
     */
    public function count($status = null)
    {
        $query = $this->relationship
            ->newQuery();

        if ($status) {
            $query->where('status', '=', $status);
        }

        return $query->count();
    }

   /**
     * Count relationships
     *
     * @var start
     * @var end
     * @return integer
     */
    public function countBetweenDates(DateTime $start, DateTime $end)
    {
        $query = $this->relationship
            ->newQuery()
            ->whereBetween('created_at', [$start, $end]);

        return $query->count();
    }

   /**
     * Get confirmed relationships betwee dates
     *
     * @var start
     * @var end
     * @return integer
     */
    public function getConfirmedBetweenDates(DateTime $start, DateTime $end)
    {

        $query = $this->relationship
            ->newQuery()
            ->where('is_confirmed', 1)
            ->whereBetween('created_at', [$start, $end]);

        return $query->get();
    }

   /**
     * Get confirmed relationships betwee dates
     *
     * @var start
     * @var end
     * @return integer
     */
    public function countConfirmedBetweenDates(DateTime $start, DateTime $end)
    {
        $query = $this->relationship
            ->newQuery()
            ->where('is_confirmed', 1)
            ->whereBetween('created_at', [$start, $end]);

        return $query->count();
    }

    /**
     * Get relationships ordered by tasks.
     *
     * @param  string $status 
     * @return Collection
     */
    public function getOrderedByTasks($status = null)
    {
        $query = $this->relationship
            ->newQuery()
            ->select($this->relationship->getTable().'.*', $this->database->raw("(
                select `action_at`
                from `tasks`
                join `taskables` on `taskables`.`task_id` = `tasks`.`id`
                where `taskables`.`taskable_id` = `relationships`.`id`
                and `taskables`.`taskable_type` = 'App\\\Relationship'
                order by `action_at` asc
                limit 1
            ) as `action_at`"))
            ->orderBy('action_at')
            ->takePage($this->page, $this->perPage)
            ->with($this->with);

        if ($status) {
            $query->where('status', '=', $status);
        }

        return $query->get();
    }

    /**
     * Get the relationships the given tutor belongs to.
     * Results are paginated and sorted by the students first name.
     *
     * @param  Tutor   $tutor
     * @param  Integer $page
     * @param  Integer $perPage
     * @return Collection
     */
    public function getByTutor(Tutor $tutor, $page, $perPage)
    {
        return $tutor
            ->relationships()
            ->join('users as students', 'students.id', '=', 'relationships.student_id')
            ->with('student')
            ->takePage($page, $perPage)
            ->orderBy('students.first_name')
            ->get([
                'relationships.*'
            ]);
    }

    /**
     * Count the number of relationships that a given tutor belongs to.
     *
     * @param  Tutor $tutor
     * @return integer
     */
    public function countByTutor(Tutor $tutor)
    {
        return $tutor
            ->relationships()
            ->has('message')
            ->count();
    }

    /**
     * Get the relationships the given tutor belongs to.
     * Results are paginated and sorted by the students first name.
     *
     * @param  Student $student
     * @param  Integer $page
     * @param  Integer $perPage
     * @return Collection
     */
    public function getByStudent(Student $student, $page, $perPage)
    {
        return $student
            ->relationships()
            ->join('users as tutors', 'tutors.id', '=', 'relationships.tutor_id')
            ->orderBy('relationships.updated_at', 'desc')
            ->takePage($page, $perPage)
            ->with(array_extend([
                'tutor'
            ], $this->with))
            ->get([
                'relationships.*'
            ]);
    }

    /**
     * Get the relationships the given user belongs to.
     * Results are paginated and sorted by the last message line created date.
     *
     * @param  User     $user
     * @param  Integer  $page
     * @param  Integer  $perPage
     *
     * @return Collection
     */
    public function getByUser(User $user, $page = null, $perPage = null)
    {
        $query = $user
            ->relationships()
            ->join('messages', 'messages.relationship_id', '=', 'relationships.id')
            ->join(DB::raw('
                (SELECT MAX(created_at) as max_created_at, message_id
                FROM message_lines
                GROUP BY message_id) last_message_lines
            '), 'last_message_lines.message_id', '=', 'messages.id')
            ->orderBy('last_message_lines.max_created_at', 'desc')
            ->groupBy('relationships.id');

        if($page && $perPage) {
            $query = $query->takePage($page, $perPage);
        }

        $query = $query->with($this->with);

        return $query->get([
            'relationships.*'
        ]);
    }

    /**
     * Find the relationship between a given tutor and student.
     *
     * @param  Tutor   $tutor
     * @param  Student $student
     * @return Relationship
     */
    public function findByTutorAndStudent(Tutor $tutor, Student $student)
    {
        return $this
            ->relationship
            ->newQuery()
            ->whereHas('tutor', function ($query) use($tutor) {
                $query->where('id', '=', $tutor->id);
            })
            ->whereHas('student', function ($query) use($student) {
                $query->where('id', '=', $student->id);
            })
            ->with(array_extend([
                'tutor',
                'student'
            ], $this->with))
            ->first();
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
        return $this->traitWithTasksNext($query, Relationship::class);
    }
}

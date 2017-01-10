<?php

namespace App\Repositories;

use DateTime;
use App\User;
use App\Tutor;
use App\Student;
use App\UserProfile;
use App\Relationship;
use App\Geocode\Location;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Traits\WithTasksTrait;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Traits\WithReviewsTrait;
use App\Repositories\Traits\WithMessageLinesTrait;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Traits\ChainableEloquentRepositoryTrait;
use App\Repositories\Traits\WithRequirementsForTrait;
use App\Repositories\Contracts\TutorRepositoryInterface;
use App\Repositories\Traits\WithRelationshipsLessonsTrait;

class EloquentTutorRepository extends AbstractEloquentLocationRepository
    implements TutorRepositoryInterface
{
    use ChainableEloquentRepositoryTrait;
    use WithRelationshipsLessonsTrait;
    use WithRequirementsForTrait;
    use WithReviewsTrait;
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
     * @var Tutor
     */
    protected $tutor;

    /**
     * Create an instance of the repository
     *
     * @param  Database $database
     * @param  Tutor    $tutor
     * @return void
     */
    public function __construct(
        Database $database,
        Tutor    $tutor
    ) {
        $this->database = $database;
        $this->tutor    = $tutor;
    }

    /**
     * Find a tutor by a given uuid
     *
     * @param  string $uuid
     * @return User|null
     */
    public function findByUuid($uuid)
    {
        return $this->tutor
            ->where('uuid', '=', $uuid)
            ->with($this->with)
            ->first();
    }

    /**
     * Find multiple users by given ids
     *
     * @param Array $ids
     * @return Collection
     */
    public function getByIds(array $ids)
    {
        return $this->tutor
            ->whereIn('id', $ids)
            ->with(
                'subjects',
                'addresses',
                'profile',
                'backgroundCheck'
            )
            ->get();
    }

    /**
     * Count the number of tutors between Dates
     *
     * @return integer
     */
    public function countBetweenDates(DateTime $start, DateTime $end)
    {
        return $this->tutor
            ->createdBetween($start, $end)
            ->count();
    }

    /**
     * Count the number of tutors between Dates
     *
     * @return integer
     */
    public function countLiveBetweenDates(DateTime $start, DateTime $end)
    {
        return $this->tutor
            ->live()
            ->createdBetween($start, $end)
            ->count();
    }

    /**
     * Get the tutors by searching the given columns for a given query.
     *
     * @param   string $query
     * @param   $columns
     * @return  Collection
     */
    public function getByQuery($term, $columns = [])
    {
        return $this->tutor
            ->where(function($query) use ($term, $columns) {
                foreach ($columns as $column) {
                    $query = $query->orWhere($column, 'LIKE', '%'.$term.'%');
                }
            })
            ->takePage($this->page, $this->perPage)
            ->get();
    }

    /**
     * Get a page of tutors ordered by date
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function get()
    {
        return $this->tutor
            ->newQuery()
            ->viewable()
            ->with($this->with)
            ->takePage($this->page, $this->perPage)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Count the number of tutors.
     *
     * @return integer
     */
    public function count()
    {
        return $this->tutor
            ->count();
    }

    /**
     * Get a page of tutors for review ordered by date
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getByReview()
    {
        
        return $this->tutor
            ->select('users.*', $this->database->raw("(
                select `action_at`
                from `tasks`
                join `taskables` on `taskables`.`task_id` = `tasks`.`id`
                where `taskables`.`taskable_id` = `users`.`id`
                and `taskables`.`taskable_type` = 'App\\\Tutor'
                order by `action_at` asc
                limit 1
            ) as `action_at`"))
            ->whereHas('profile', function ($query) {
                return $query->where('admin_status', '=', UserProfile::REVIEW)
                             ->where('status', '=', UserProfile::PENDING)
                             ->notRejected();
            })
            ->with($this->with)
            ->takePage($this->page, $this->perPage)
            ->orderBy('action_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Count the tutors for review.
     *
     * @return integer
     */
    public function countByReview()
    {
        return $this->tutor
            ->whereHas('profile', function ($query) {
                return $query->where('admin_status', '=', UserProfile::REVIEW)
                             ->where('status', '=', UserProfile::PENDING)
                             ->notRejected();
            })
            ->count();
    }


    /**
     * Get a page of tutors ordered by date with task
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getByTask()
    {
        return $this->tutor
            ->select('users.*', $this->database->raw("(
                select `action_at`
                from `tasks`
                join `taskables` on `taskables`.`task_id` = `tasks`.`id`
                where `taskables`.`taskable_id` = `users`.`id`
                and `taskables`.`taskable_type` = 'App\\\Tutor'
                order by `action_at` asc
                limit 1
            ) as `action_at`"))
            ->viewable()
            ->accepted()
            ->with($this->with)
            ->has('tasks')
            ->takePage($this->page, $this->perPage)
            ->orderBy('action_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Count the number of tutors with a task
     *
     * @return integer
     */
    public function countByTask()
    {
        return $this->countByTaskCategory(null);
    }

    /**
     * Get a page of tutors ordered by date with task type rebook
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getByTaskCategoryRebook()
    {
        return $this->getByTaskCategory('rebook');
    }

    /**
     * Count the number of tutors with a task
     *
     * @return integer
     */
    public function countByTaskCategoryRebook()
    {
        return $this->countByTaskCategory('rebook');
    }

    /**
     * Get a page of tutors ordered by date with task type cancelled task
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getByTaskCategoryCancelled()
    {
        return $this->getByTaskCategory('cancelled_first_lesson');
    }

    /**
     * Count the number of tutors with a cancelled task
     *
     * @return integer
     */
    public function countByTaskCategoryCancelled()
    {
        return $this->countByTaskCategory('cancelled_first_lesson');
    }



    /**
     * Get a page of tutors ordered by date with task type first lesson
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getByTaskCategoryFirst()
    {
        return $this->getByTaskCategory('first_lesson');
    }

    /**
     * Count the number of tutors with a task category of first lesson
     *
     * @return integer
     */
    public function countByTaskCategoryFirst()
    {
        return $this->countByTaskCategory(['first_lesson_rebook', 'first_lesson_no_rebook', 'first_lesson']);
    }

    /**
     * Get a page of tutors ordered by date with task type first lesson
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getByTaskCategoryFirstWithStudent()
    {
        return $this->getByTaskCategory(['first_with_student_no_rebook', 'first_with_student_rebook', 'first_with_student']);
    }

    /**
     * Count the number of tutors with a task category of first lesson
     *
     * @return integer
     */
    public function countByTaskCategoryFirstWithStudent()
    {
        return $this->countByTaskCategory(['first_with_student_no_rebook', 'first_with_student_rebook', 'first_with_student']);
    }

    /**
     * Get a page of tutors ordered by date with task type refund
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getByTaskCategoryRefund()
    {
        return $this->getByTaskCategory('refund');
    }

    /**
     * Count the number of tutors with a task category of refund
     *
     * @return integer
     */
    public function countByTaskCategoryRefund()
    {
        return $this->countByTaskCategory('refund');
    }

    /**
     * Get a page of tutors ordered by date with task type disintermediating
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getByTaskCategoryDisintermediating()
    {
        return $this->getByTaskCategory('disintermediating');
    }

    /**
     * Count the number of tutors with a task category of disintermediating
     *
     * @return integer
     */
    public function countByTaskCategoryDisintermediating()
    {
        return $this->countByTaskCategory('disintermediating');
    }


    /**
     * Get a page of tutors ordered by date with task type lesson count
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getByTaskCategoryLessonCount()
    {
        return $this->getByTaskCategory('lesson_count');
    }

    /**
     * Count the number of tutors with a task category of first lesson
     *
     * @return integer
     */
    public function countByTaskCategoryLessonCount()
    {
        return $this->countByTaskCategory('lesson_count');
    }

    /**
     * Get a page of tutors ordered by date with task type transgression
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getByTaskCategoryTransgression()
    {
        return $this->getByTaskCategory('transgression');
    }

    /**
     * Count the number of tutors with a task category of first lesson
     *
     * @return integer
     */
    public function countByTaskCategoryTransgression()
    {
        return $this->countByTaskCategory('transgression');
    }

    /**
     * Get a page of tutors ordered by date with task category
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function getByTaskCategory($category)
    {
        if (! is_array($category)) $category = [$category];

        if (count($category) == 1) $addWhereTaskCategory = "and `tasks`.`category` = '$category[0]'";
        if (count($category) == 2) $addWhereTaskCategory = "and (`tasks`.`category` = '$category[0]' or `tasks`.`category` = '$category[1]')";
        if (count($category) == 3) $addWhereTaskCategory = "and (`tasks`.`category` = '$category[0]' or `tasks`.`category` = '$category[1]' or `tasks`.`category` = '$category[2]')";

        dump($addWhereTaskCategory);
        return $this->tutor
            ->select('users.*', $this->database->raw("(
                select `action_at`
                from `tasks`
                join `taskables` on `taskables`.`task_id` = `tasks`.`id`
                where `taskables`.`taskable_id` = `users`.`id`
                and `taskables`.`taskable_type` = 'App\\\Tutor'
                $addWhereTaskCategory
                order by `action_at` asc
                limit 1
            ) as `action_at`"))
            ->viewable()
            ->accepted()
            ->with($this->with)
            ->whereHas('tasks', function ($q) use ($category) {
                if (! $category) {
                    null;
                } else {
                    $q->whereIn('category', $category);
                }
            })
            ->takePage($this->page, $this->perPage)
            ->orderBy('action_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Count the number of tutors with a particular task category
     *
     * @param  integer $page
     * @param  integer $perPage
     * @return Collection
     */
    public function countByTaskCategory($category)
    {
        if (! is_array($category)) $category = [$category];

        if (count($category) == 1) $addWhereTaskCategory = "and `tasks`.`category` = '$category[0]'";
        if (count($category) == 2) $addWhereTaskCategory = "and (`tasks`.`category` = '$category[0]' or `tasks`.`category` = '$category[1]')";
        if (count($category) == 3) $addWhereTaskCategory = "and (`tasks`.`category` = '$category[0]' or `tasks`.`category` = '$category[1]' or `tasks`.`category` = '$category[2]')";

        return $this->tutor
            ->select('users.*', $this->database->raw("(
                select `action_at`
                from `tasks`
                join `taskables` on `taskables`.`task_id` = `tasks`.`id`
                where `taskables`.`taskable_id` = `users`.`id`
                and `taskables`.`taskable_type` = 'App\\\Tutor'
                $addWhereTaskCategory
                order by `action_at` asc
                limit 1
            ) as `action_at`"))
            ->viewable()
            ->accepted()
            ->with($this->with)
            ->whereHas('tasks', function ($q) use ($category) {
                if (! $category) {
                    null;
                } else {
                    $q->whereIn('category', $category);
                }
            })            
            ->count();
    }



    /**
     * Count the tutors who are live. Used in analytics
     *
     * @return integer
     */
    public function countLive()
    {
        return $this->tutor
            ->whereHas('profile', function ($query) {
                return $query->where('admin_status', '=', UserProfile::OK)
                             ->where('status', '=', UserProfile::LIVE);
                            
            })
            ->count();
    }

    /**
     * Get a page of tutors by Booking score
     *
     * @return Collection
     */
    public function getByBookingScore()
    {
       return $this->tutor
            ->live()
            ->whereHas('profile', function($q) {
                 $q->where('quality', '!=', '0');
            })
            ->select('users.*', $this->database->raw("(
                select `booking_score`
                from `user_profiles`
                where `user_profiles`.`user_id` = `users`.`id`
            ) as `booking_score`"))
            ->orderBy('booking_score')
            ->takePage($this->page, $this->perPage)
            ->get();
    }

    /**
     * Count tutors by booking score
     *
     * @return Collection
     */
    public function countByBookingScore()
    {
       return $this->tutor
            ->live()
            ->whereHas('profile', function($q) {
                 $q->where('quality', '!=', '0');
            })
           
            ->count();
    }

    /**
     * Get a page of tutors by Booking score
     *
     * @return Collection
     */
    public function getByProfileScore()
    {
       return $this->tutor
            ->live()
            ->whereHas('profile', function($q) {
                 $q->where('quality', '!=', '0');
            })
            ->select('users.*', $this->database->raw("(
                select `profile_score`
                from `user_profiles`
                where `user_profiles`.`user_id` = `users`.`id`
            ) as `profile_score`"))
            ->orderBy('profile_score')
            ->takePage($this->page, $this->perPage)
            ->get();
    }

    /**
     * Count tutors by profile score
     *
     * @return Collection
     */
    public function countByProfileScore()
    {
       return $this->tutor
            ->live()
            ->whereHas('profile', function($q) {
                 $q->where('quality', '!=', '0');
            })
           
            ->count();
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
        return $this->traitWithTasksNext($query, Tutor::class);
    }

    /**
     * Select only the next task due.
     *
     * @param  MorphMany $query
     * @param  string    $taskableType = taskable_type
     * @return MorphMany
     */
    protected function withRelationshipsTasksNext($query)
    {
        return $this->traitWithTasksNext($query, Relationship::class);
    }


    /**
     * Select only the last message line
     *
     * @param  MorphMany $query
     * @return MorphMany
     */
    protected function withRelationshipsMessageLinesLast($query)
    {
        return $this->withMessageLinesLast($query);
    }

    /**
     * Get the tutors belonging to a student
     */
    public function getByStudent(Student $student, $page = null, $perPage = null)
    {
        $tutors = $student
            ->relationships()
            ->has('tutor')
            ->with('tutor')
            ->orderBy('created_at', 'desc');

        if($page && $perPage) {
            $tutors = $tutors->takePage($page, $perPage);
        }

        $tutors = $tutors->lists('tutor_id');

        return $this->tutor
            ->newQuery()
            ->with(array_extend([
                'relationships' => function ($query) use ($student) {
                    return $query->where('student_id', '=', $student->id);
                }
            ], $this->with))
            ->select('users.*')
            ->join('relationships', 'relationships.tutor_id', '=', 'users.id')
            ->where('relationships.student_id', '=', $student->id)
            ->orderBy('relationships.created_at', 'desc')
            ->whereIn('users.id', $tutors->toArray())
            ->get();
    }

    /**
     * Count the number of tutors that belong to a given student
     *
     * @param  Student $student
     * @return integer
     */
    public function countByStudent(Student $student)
    {
        return $student
            ->relationships()
            ->has('tutor')
            ->count();
    }

    /**
     * Order by tasks
     *
     * @return $this
     */
    protected function withFirstTask() 
    {
        $this->query->select('users.*', $this->database->raw("(
                select `action_at`
                from `tasks`
                where `tasks`.`taskable_id` = `users`.`id`
                and `tasks`.`taskable_type` = 'App\\\Tutor'
                order by `action_at` asc
                limit 1
            ) as `action_at`"));

        return $this;
    }

     /**
     * Order by tasks and then created at date
     *
     * @return $this
     */   

    protected function orderByTaskThenCreated()
    {
        $this->query->orderBy('action_at')
            ->orderBy('users.created_at');

        return $this;
    }  

    protected function wherePendingReview()
    {
        $this->query->whereHas('profile', function ($query) {
                return $query->where('admin_status', '=', UserProfile::REVIEW)
                             ->where('status', '=', UserProfile::PENDING)
                             ->notRejected();
            });

        return $this;
    }

    protected function whereOffline()
    {
        $this->query->whereHas('profile', function ($query) {
                return $query->offline()
                    ->notRejected();
            });

        return $this;
    }

    protected function whereStatus($status)
    {
        $this->query->where('status', '=', $status);

        return $this;
    }

    // SEARCH ///////////////////////////////////////////////////////////

    public function getByBestMatch(
        Collection $subjects = null,
        Location   $location = null,
        $page,
        $perPage
    ) {
        $query = $this->bySubjectsAndLocation($subjects, $location);

        $db = $this->database->getDatabaseName();

        $score = "user_profiles.profile_score";
        $distanceInflectionPoint = config('algorithm.distanceInfectionPoint');

        if ($location !== null) {
            $score = $score." * `{$db}`.DISTANCE_SCORE(`{$db}`.DISTANCE(
                {$location->latitude},
                {$location->longitude},
                addresses.latitude,
                addresses.longitude
            ), $distanceInflectionPoint)";
        }

        $results = $query
            ->addSelect($this->database->raw($score.' AS score'))
            ->groupBy('users.id')
            ->orderBy('score', 'desc')
            ->take($perPage)
            ->skip(($page - 1) * $perPage)
            ->get();

        return $this->getByResults($results, 'score', true);
    }

    public function countByBestMatch(
        Collection $subjects = null,
        Location   $location = null
    ) {
        return $this->countBySubjectsAndLocation($subjects, $location);
    }

    public function getByRating(
        Collection $subjects = null,
        Location   $location = null,
        $page,
        $perPage
    ) {
        $query = $this->bySubjectsAndLocation($subjects, $location);

        $results = $query
            ->addSelect($this->database->raw('user_profiles.rating AS score'))
            ->groupBy('users.id')
            ->orderBy('score', 'desc')
            ->take($perPage)
            ->skip(($page - 1) * $perPage)
            ->get();

        return $this->getByResults($results, 'score', true);

    }

    public function countByRating(
        Collection $subjects = null,
        Location   $location = null
    ) {
        return $this->countBySubjectsAndLocation($subjects, $location);
    }

    public function getByDistance(
        Collection $subjects = null,
        Location   $location = null,
        $page,
        $perPage
    ) {
        $query = $this->bySubjectsAndLocation($subjects, $location);

        $results = $query
            ->groupBy('users.id')
            ->orderBy('distance', 'asc')
            ->take($perPage)
            ->skip(($page - 1) * $perPage)
            ->get();

        return $this->getByResults($results, 'distance', false);
    }

    public function countByDistance(
        Collection $subjects = null,
        Location   $location = null
    ) {
        return $this->countBySubjectsAndLocation($subjects, $location);
    }

    public function getByPendingReview($page, $perPage)
    {
        return $this->tutor
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->where('user_profiles.reviewed', '=', false)
            ->where('user_profiles.rejected', '=', false)
            ->where('user_profiles.live', '=', true)
            ->takePage($page, $perPage)
            ->get();
    }

    protected function countBySubjectsAndLocation(
        Collection $subjects = null,
        Location   $location = null
    ) {
        $results = $this->bySubjectsAndLocation($subjects, $location)
            ->select($this->database->raw('count(DISTINCT `users`.`id`) AS `count`'))
            ->first();

        return $results ? $results->count : 0;
    }

    public function countReviewsBySubjectsAndLocation(
        Collection $subjects = null,
        Location   $location = null
    ) {
        $results = $this->bySubjectsAndLocation($subjects, $location)
            ->select($this->database->raw('SUM(`user_profiles`.`ratings_count`) AS `review_count`'))
            ->first();

        return $results ? $results->review_count : 0;
    }

    public function averageReviewBySubjectsAndLocation(
        Collection $subjects = null,
        Location   $location = null
    ) {
        $results = $this->bySubjectsAndLocation($subjects, $location)
            ->select($this->database->raw('SUM(`user_profiles`.`rating` * `user_profiles`.`ratings_count`) / SUM(`user_profiles`.`ratings_count`) AS `average_review`'))
            ->first();

        return $results ? $results->average_review : 0;
    }

    protected function bySubjectsAndLocation(
        Collection $subjects = null,
        Location   $location = null
    ) {
        $query = $this->database
            ->table('users')
            ->select('users.id as user_id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->where('user_profiles.admin_status', '=', 'ok')
            ->where('user_profiles.status', '=', 'live')
            ->where('user_profiles.profile_score', '>=', config('algorithm.minProfileScore'))
            ->where('user_profiles.booking_score', '>=', config('algorithm.minBookingScore'))
            ->whereNull('users.deleted_at');

        if ($subjects !== null) {
            $query = $this->addWhereSubjects($query, $subjects);
        }

        if ($location !== null) {
            $query = $this->addWhereLocation($query, $location);
        }

        return $query;
    }

    protected function addWhereSubjects($query, $subjects)
    {
        return $query
            ->join('subject_user', 'subject_user.user_id', '=', 'users.id')
            ->whereIn('subject_user.subject_id', $subjects->lists('id'));
    }

    protected function getByResults(Array $results, $sortByColumn, $sortByDesc)
    {
        $mappedResults = [];
        foreach($results as $result) {
            $mappedResults[$result->user_id] = $result;
        }

        return $this
            ->getByIds(array_pluck($results, 'user_id'))
            ->map(function ($tutor) use ($mappedResults) {
                $result = $mappedResults[$tutor->id];

                $tutor->distance = isset($result->distance) ? $result->distance : null;
                $tutor->score    = isset($result->score) ? $result->score : null;

                return $tutor;
            })
            ->sortBy($sortByColumn, null, $sortByDesc);
    }

    // Analytics & Algorithm //////////////////////////////////////////

}

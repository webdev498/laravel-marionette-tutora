<?php

namespace App\Repositories;

use App\Lesson;
use App\LessonBooking;
use App\Relationship;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Student;
use App\Tutor;
use DateTime;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Database\Eloquent\Collection;

class EloquentLessonBookingRepository extends AbstractEloquentRepository implements
    LessonBookingRepositoryInterface
{

    /**
     * @var Builder $query
     */
    protected $query;

    /*
     * @var Database
     */
    protected $database;

    /*
     * @var LessonBooking
     */
    protected $booking;

    /**
     * Create an instance of this
     *
     * @param  Database $databse
     * @param  LessonBooking $booking
     * @return void
     */
    public function __construct(Database $database, LessonBooking $booking)
    {
        $this->database = $database;
        $this->booking  = $booking;
    }

    /**
     * Persist a lesson booking to the database
     *
     * @param  LessonBooking $booking
     * @return LessonBooking|null
     */
    public function save(LessonBooking $booking)
    {
        if ( ! $booking->save()) {
            throw new ResourceNotPersisted();
        }

        return $booking;
    }

    /**
     * Find a lesson booking by id.
     *
     * @param  Integer $id
     * @return LessonBooking|null
     */
    public function findById($id)
    {
        return $this->booking
            ->where('id', '=', $id)
            ->with($this->with)
            ->first();
    }

    /**
     * Find a booking by a given uuid
     *
     * @param string $uuid
     * @return LessonBooking|null
     */
    public function findByUuid($uuid)
    {
        return $this->booking
            ->where('uuid', '=', $uuid)
            ->with($this->with)
            ->first();
    }

    /**
     * Find a booking by a given charge_id
     *
     * @param string $charge
     * @return LessonBooking|null
     */
    public function findByChargeId($charge_id)
    {
        return $this->booking
            ->where('charge_id', '=', $charge_id)
            ->with($this->with)
            ->first();
    }

    /**
     * Find a booking by a given payment_id
     *
     * @param string $payment
     * @return LessonBooking|null
     */
    public function findByPaymentId($payment_id)
    {
        return $this->booking
            ->where('payment_id', '=', $payment_id)
            ->with($this->with)
            ->first();
    }

    /**
     * Count the number of bookings with a given uuid
     */
    public function countByUuid($uuid)
    {
        return $this->booking
            ->where('uuid', '=', $uuid)
            ->count();
    }

    /**
     * Get all of the lessons
     *
     * @return Collection
     */
    public function get()
    {
        return $this->booking
            ->newQuery()
            ->with($this->with)
            ->takePage($this->page, $this->perPage)
            ->get();
    }

    /**
     * Count all of the lessons
     *
     * @return integer
     */
    public function count()
    {
        return $this->booking
            ->newQuery()
            ->count();
    }

    /**
     * Get lessons that have completed
     *
     * @param  string $status
     * @param  string $chargeStatus
     * @return Collection
     */
    public function getByStatus($status, $chargeStatus = null)
    {
        switch ($status) {
            case LessonBooking::PENDING:
            case LessonBooking::CONFIRMED:
            case LessonBooking::COMPLETED:
                $order = 'start_at';
                $sort  = 'asc';
                break;
            default:
                $order = 'updated_at';
                $sort  = 'desc';
        }

        return $this->booking
            ->newQuery()
            ->where(function ($query) use ($status) {
                if ($status) {
                    $query->where('status', '=', $status);
                }
                return $query;
            })
            ->where(function ($query) use ($chargeStatus) {
                if ($chargeStatus) {
                    $query->where('charge_status', '=', $chargeStatus);
                }
                return $query;
            })
            ->with($this->with)
            ->takePage($this->page, $this->perPage)
            ->orderBy($order, $sort)
            ->get();
    }

    /**
     * Count by status and charge status
     *
     * @param  string $status
     * @param  string $chargeStatus
     * @return integer
     */
    public function countByStatus($status, $chargeStatus = null)
    {
        return $this->booking
            ->newQuery()
            ->where(function ($query) use ($status) {
                if ($status) {
                    $query->where('status', '=', $status);
                }
                return $query;
            })
            ->where(function ($query) use ($chargeStatus) {
                if ($chargeStatus) {
                    $query->where('charge_status', '=', $chargeStatus);
                }
                return $query;
            })
            ->count();
    }



    /**
     * Get By Charge Status
     *
     * @param  [Array] or String: $chargeStatus
     * @return integer
     */
    public function getByChargeStatus($chargeStatus)
    {
            
        if (is_string($chargeStatus)) {
            $chargeStatus = [$chargeStatus];
        }

        return $this->booking
            ->newQuery()
            ->whereIn('charge_status', $chargeStatus)
            ->get();
    }

    /**
     * Get all of the lessons by a given relationship.
     *
     * @param  Relationship $relationship
     * @return Collection
     */
    public function getByRelationship(Relationship $relationship)
    {
        $query = $this->newQuery()
            ->byRelationship($relationship);

        return $this->query->get();
    }

    /**
     * Get all of the lessons by a given relationship and status
     *
     * @param  Relationship $relationship
     *
     * @return Collection
     */
    public function getByRelationshipAndStatus(Relationship $relationship, $status, $start = null, $end = null)
    {
        $this->newQuery()
            ->byRelationship($relationship)
            ->where(function($query) use ($status) {
                if (is_array($status)) {
                    $query->whereIn('status', $status);
                } else {
                    $query->where('status', '=', $status);
                }
            })
            ->where(function ($query) use ($start, $end) {
                if ($start && $end) {
                    $query->whereBetween('finish_at', [$start, $end]);
                }
                return $query;
            })
            ->orderBy('finish_at', 'asc');
            

        return $this->query->get();
    }

    /**
     * Get all of the lessons by a given relationship and status
     *
     * @param  Relationship $relationship
     * @param  mixed        $start
     * @param  mixed        $end
     *
     * @return integer
     */
    public function countByRelationshipAndStatus(Relationship $relationship, $status, $start = null, $end = null)
    {
        $query = $this->booking
            ->newQuery()
            ->hasRelationship($relationship)
            ->where(function($query) use ($status) {
                if (is_array($status)) {
                    $query->whereIn('status', $status);
                } else {
                    $query->where('status', '=', $status);
                }
            })
            ->where(function ($query) use ($start, $end) {
                if ($start && $end) {
                    $query->whereBetween('finish_at', [$start, $end]);
                }
                return $query;
            });


        return $query->count();
    }

    public function getByRelationshipAndDate(Relationship $relationship, DateTime $date)
    {

        return $this->booking
            ->newQuery()
            ->select('*')
            ->addSelectHasPassed()
            ->hasRelationship($relationship)
            ->with($this->with)
            ->WhereHasntPassed()
            ->where('status', '!=', 'cancelled')
            ->orderByHasPassed()
            ->orderByFinishAt()
            ->takePage($this->page, $this->perPage)
            ->get();
    }

    /**
     * Count the number of bookings belonging to a given relationship
     *
     * @param  Relationship $relationship
     * @return integer
     */
    public function countByRelationship(Relationship $relationship)
    {
        return $this->booking
            ->newQuery()
            ->hasRelationship($relationship)
            ->count();
    }

    /**
     * Get all the lessons by a given tutor.
     *
     * @param  Tutor   $tutor
     * @param  Integer $page
     * @param  Integer $perPage
     * @return Collection
     */
    public function getByTutor(Tutor $tutor, $page, $perPage)
    {
        return $this->booking
            ->select('*')
            ->addSelectHasPassed()
            ->hasTutor($tutor)
            ->with($this->with)
            ->orderByHasPassed()
            ->orderByFinishAt()
            ->takePage($page, $perPage)
            ->get();
    }

    /**
     * Get all the lessons by a given tutor.
     *
     * @param  Tutor   $tutor
     * @param  String  $status
     * @param  Integer $page
     * @param  Integer $perPage
     * @return Collection
     */
    public function getByTutorByStatus(Tutor $tutor, $status, $page, $perPage)
    {
        return $this->booking
            ->select('*')
            ->addSelectHasPassed()
            ->hasTutor($tutor)
            ->with($this->with)
            ->status($status)
            ->orderByHasPassed()
            ->orderByFinishAt()
            ->takePage($page, $perPage)
            ->get();
    }

    /**
     * Count the number of bookings a given tutor has.
     *
     * @param  Tutor $tutor
     * @return integer
     */
    public function countByTutor(Tutor $tutor)
    {
        return $this->booking
            ->hasTutor($tutor)
            ->count();
    }


    /**
     * Count the number of bookings a given tutor has for a particular status.
     *
     * @param  Tutor $tutor
     * @param  String $status
     * @return integer
     */
    public function countByTutorByStatus(Tutor $tutor, $status)
    {
        return $this->booking
            ->hasTutor($tutor)
            ->status($status)
            ->count();
    }
    /**
     * Count the number of completed lessons by a tutor after a given date.
     * Useful when calculating fees.
     *
     * @param  Tutor    $tutor
     * @param  DateTime $date
     * @return integer
     */
    public function countCompletedAfterDateByTutor(Tutor $tutor, DateTime $date)
    {
        return $this->booking
            ->hasTutor($tutor)
            ->where('status', '=', LessonBooking::COMPLETED)
            ->count();
    }

    /**
     * Get all the lessons by a given student.
     *
     * @param  Student $student
     * @param  Integer $page
     * @param  Integer $perPage
     * @return Collection
     */
    public function getByStudent(Student $student, $page, $perPage)
    {
        return $this->booking
            ->select('*')
            ->addSelectHasPassed()
            ->hasStudent($student)
            ->with($this->with)
            ->orderByHasPassed()
            ->orderByFinishAt()
            ->takePage($page, $perPage)
            ->get();
    }

    /**
     * Get all the lessons by a given student by status.
     *
     * @param  Student $student
     * @param  String $status
     * @param  Integer $page
     * @param  Integer $perPage
     * @return Collection
     */
    public function getByStudentByStatus(Student $student, $status, $page, $perPage)
    {
        return $this->booking
            ->select('*')
            ->addSelectHasPassed()
            ->hasStudent($student)
            ->with($this->with)
            ->status($status)
            ->orderByHasPassed()
            ->orderByFinishAt()
            ->takePage($page, $perPage)
            ->get();
    }

    /**
     * Count the number of bookings a given student has.
     *
     * @param  Student $student
     * @return integer
     */
    public function countByStudent(Student $student)
    {
        return $this->booking
            ->hasStudent($student)
            ->count();
    }

    /**
     * Count the number of bookings a given student has.
     *
     * @param  Student $student
     * @param  string $status
     * @return integer
     */
    public function countByStudentByStatus(Student $student, $status)
    {
        return $this->booking
            ->hasStudent($student)
            ->status($status)
            ->count();
    }

    /**
     * Get the lessons that are rebillable. As in, lessons that
     * need to set to be billed again.
     *
     * @param Student $student
     * @return Collection
     */
    public function getRebillableByStudent(Student $student)
    {
        return $this->booking
            ->hasStudent($student)
            ->with($this->with)
            ->whereIn('charge_status', [
                LessonBooking::AUTHORISED,
                LessonBooking::AUTHORISATION_FAILED,
                LessonBooking::PAYMENT_FAILED,
            ])
            ->get();
    }

    /**
     * Get the lessons that are completable. As in, lessons that
     * need to be competable.
     *
     * @param  DateTime $date
     * @return Collection
     */
    public function getCompletableBeforeDate(DateTime $date)
    {
        return $this->booking
            ->where('finish_at', '<=', $date)
            ->where('status', '=', LessonBooking::CONFIRMED)
            ->where('charge_status', '!=', LessonBooking::PENDING)
            ->get();
    }

    /**
     * Get the lessons that are cancellable. As in, lessons that
     * need to be cancelled.
     *
     * @param  DateTime $date
     * @return Collection
     */
    public function getCancellableBeforeDate(DateTime $date)
    {
        return $this->booking
            ->where('start_at', '<=', $date)
            ->where('charge_status', '=', LessonBooking::PENDING)
            ->get();
    }

    /**
     * Get the lessons that are pending authorisation.
     *
     * @param  DateTime $date
     * @return Collection
     */
    public function getAuthorisableBeforeDate(DateTime $date)
    {
        return $this->booking
            ->with($this->with)
            ->where('start_at', '<=', $date)
            ->whereIn('charge_status', [
                LessonBooking::AUTHORISATION_PENDING,
            ])
            ->get();
    }

    /**
     * Get the lessons that have failed authorisation before date
     *
     * @param  DateTime $date
     * @return Collection
     */
    public function getAuthorisationFailedBeforeDate(DateTime $date)
    {
        return $this->booking
            ->with($this->with)
            ->where('start_at', '<=', $date)
            ->whereIn('charge_status', [
                LessonBooking::AUTHORISATION_FAILED,
            ])
            ->get();
    }

    /**
     * Get the lessons that are authorised awaiting capture.
     *
     * @param  DateTime $date
     * @return Collection
     */
    public function getCapturableBeforeDate(DateTime $date)
    {
        return $this->booking
            ->with($this->with)
            ->where('finish_at', '<=', $date)
            ->whereIn('charge_status', [
                LessonBooking::AUTHORISED,
            ])
            ->get();
    }

    /**
     * Get the lessons that are authorised awaiting capture.
     *
     * @param  DateTime $date
     * @return Collection
     */
    public function getChargableBeforeDate(DateTime $date)
    {
        return $this->booking
            ->with($this->with)
            ->where('finish_at', '<=', $date)
            ->whereIn('charge_status', [
                LessonBooking::PAYMENT_PENDING,
            ])
            ->get();
    }

    /**
     * Get the next bookings by lesson and start at date.
     *
     * @param  Lesson   $lesson           Get bookings belonging to this lesson.
     * @param  DateTime $date             Get bookings after this date.
     * @param  Boolean  $allowCurrentDate Should the booking matching $startAt
     *                                    also be returned?
     * @return Collection
     */
    public function getByLessonAndStartAfterDate(
        Lesson   $lesson,
        DateTime $date,
        $allowCurrentDate = false
    ) {
        return $this->byLessonAndStartAfterDate(
                $lesson,
                $date,
                $allowCurrentDate
            )
            ->get();
    }

    /**
     * Delete the next bookings by lesson and start at date.
     *
     * @param  Lesson   $lesson           Delete bookings belonging to this lesson.
     * @param  DateTime $date             Delete bookings after this date.
     * @param  Boolean  $allowCurrentDate Should the booking matching $startAt
     *                                    also be deleted?
     * @return Integer                    Number of records deleted.
     */
    public function deleteByLessonAndStartAfterDate(
        Lesson   $lesson,
        DateTime $date,
        $allowCurrentDate = false
    ) {
        return $this->byLessonAndStartAfterDate(
                $lesson,
                $date,
                $allowCurrentDate
            )
            ->delete();
    }
    // Scopes ////////////////////////////////////////////////////////////////////////

    protected function newQuery()
    {
        $this->query = $this->booking->newQuery();
        return $this;
    }

    /**
     * Scope all of the lessons by a given relationship.
     *
     * @param  Relationship $relationship
     * @return Collection
     */
    protected function ByRelationship(Relationship $relationship)
    {
        return $this->query
            ->select('*')
            ->with($this->with)
            ->addSelectHasPassed()
            ->hasRelationship($relationship)
            ->orderByHasPassed()
            ->orderByFinishAt()
            ->takePage($this->page, $this->perPage);
    }

    /**
     * Start the query for *byLessonAndStartAfterDate methods.
     *
     * @param  Lesson   $lesson           Get bookings belonging to this lesson
     * @param  DateTime $startAt          Get bookings after this date
     * @param  Boolean  $allowCurrentDate Should the booking matching $startAt
     * @return Builder
     */
    protected function byLessonAndStartAfterDate(
        Lesson   $lesson,
        DateTime $date,
        $allowCurrentDate = false
    ) {
        return $this->booking
            ->whereHas('lesson', function ($query) use ($lesson) {
                return $query->where('id', '=', $lesson->id);
            })
            ->where('start_at', $allowCurrentDate ? '>=' : '>', $date);
    }

    protected function whereFinishBetween(DateTime $start, DateTime $end)
    {
        $this->finishAtStart = $start;
        $this->finishAtEnd = $end;
    }


    // Analytics //////////////////////////////////////////////////////////////////

        /**
     * Count by status, charge status, and dates
     *
     * @param  string $status
     * @param  string $chargeStatus
     * @return integer
     */
    public function countByStatusBetweenDates(
        $status, 
        $chargeStatus = null, 
        DateTime $startDate, 
        DateTime $endDate
    ) {
        return $this->booking
            ->whereEqualTo('status', $status)
            ->where(function ($query) use ($chargeStatus) {
                if ($chargeStatus) {
                    $query->whereEqualTo('charge_status', $chargeStatus);
                }
                return $query;
            })
            ->whereBetween('finish_at', [$startDate, $endDate])
            ->count();
    }


    /**
     * Count Value by status, charge status, and dates. Used for analytics
     *
     * @param  string $status
     * @param  string $chargeStatus
     * @return integer
     */
    public function valueByStatusBetweenDates(
        $status, 
        $chargeStatus = null, 
        DateTime $start, 
        DateTime $end
    ) {
        return $this->booking
            ->whereEqualTo('status', $status)
            ->where(function ($query) use ($chargeStatus) {
                if ($chargeStatus) {
                    $query->whereEqualTo('charge_status', $chargeStatus);
                }
                return $query;
            })
            ->whereBetween('finish_at', [$start, $end])
            ->sum('price');
    }

    /**
     * Count by subject between dates
     *
     * @param  string $status
     * @param  string $chargeStatus
     * @return integer
     */
    public function countByStatusBySubjectsBetweenDates(
        $status,
        $chargeStatus,
        Collection $subjects,
        DateTime $start,
        DateTime $end
    ) {
        return $this->booking
            ->join('lessons', 'lessons.id', '=', 'lesson_bookings.lesson_id')
            ->whereEqualTo('lesson_bookings.status', $status)
            ->where(function ($query) use ($chargeStatus) {
                if ($chargeStatus) {
                    $query->whereEqualTo('lesson_bookings.charge_status', $chargeStatus);
                }
                return $query;
            })
            ->whereBetween('finish_at', [$start, $end])
            ->whereIn('lessons.subject_id', $subjects->lists('id'))
            ->count();
    }


    /**
     * Count by status, charge status, and dates
     *
     * @param  string $status
     * @param  string $chargeStatus
     * @return integer
     */
    public function countByStatusWithinPeriodWhereStudentCreatedBetween(
        $status, 
        $chargeStatus = null, 
        $period,
        DateTime $start, 
        DateTime $end
    ) {
        return $this->booking
            ->select('lesson_bookings.id')
            ->join('lessons', 'lessons.id', '=', 'lesson_bookings.lesson_id')
            ->join('relationships', 'relationships.id', '=', 'lessons.relationship_id')
            ->join('users', 'users.id', '=', 'relationships.student_id')
            ->whereEqualTo('lesson_bookings.status', $status)
            ->where(function ($query) use ($chargeStatus) {
                if ($chargeStatus) {
                    $query->whereEqualTo('charge_status', $chargeStatus);
                }
                return $query;
            })
            ->where('users.created_at', '>', $start)
            ->where('users.created_at', '<', $end)
            ->whereRaw("`lesson_bookings`.`finish_at` < (`users`.`created_at` + INTERVAL $period) ")
            ->count();
    }

}

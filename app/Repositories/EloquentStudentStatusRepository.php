<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Student;
use App\LessonBooking;
use App\Relationship;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Contracts\StudentStatusRepositoryInterface;

class EloquentStudentStatusRepository extends AbstractEloquentRepository
    implements StudentStatusRepositoryInterface
{


    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Student
     */
    protected $student;

    /**
     * @var Query
     */
    protected $query;
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

    public function getMismatchedStudents()
    {
        $this->newQuery()
            
            ->whereHasNoChattingRelationships()
            ->whereHasNoConfirmedRelationships()
            ->whereHasNoPendingRelationships()
            ->whereCreatedWithin('-2 weeks')
            ->whereStatusNot('closed')
            ->whereStatusNot('mismatched');

        return $this->query->get();
    }

    public function getChattingStudents()
    {
        $this->newQuery()
            ->hasNoFailedLessons()
            ->hasNoPendingLessons()
            ->hasNoCompletedLessons()
            ->whereHasNoMismatchedRelationships()
            ->whereStatusNot('expired')
            ->whereStatusNot('no_message')
            ->whereStatusNot('closed')
            ->whereStatusNot('chatting');

        return $this->query->get();
    }

    public function getPendingStudents()
    {
        $this->newQuery()
            ->hasPendingLessons()
            ->hasNoFailedLessons()
            ->whereStatusNot('pending')
            ->whereStatusNot('failed');

        return $this->query->get();
    }

    public function getConfirmedStudents()
    {
        $this->newQuery()
            ->hasConfirmedLessons()
            ->hasNoLessonSchedule()
            ->hasNoFailedLessons()
            ->whereStatusNot('confirmed');
        return $this->query->get();
    }

    public function getRecurringStudents()
    {
        $this->newQuery()
            ->hasLessonSchedule()
            ->hasConfirmedLessons()
            ->hasNoPendingLessons()
            ->hasNoFailedLessons()
            ->whereStatusNot('recurring');

        return $this->query->get();
    }

    public function getNoMessageStudents()
    {
        $this->newQuery()
            ->hasNoRelationships()
            ->whereStatusNot('closed')
            ->whereStatusNot('no_message');

        return $this->query->get();
    }


    public function getRebookPlusStudents()
    {
        $this->newQuery()
            ->hasCompletedLessons(200)
            ->whereLastLessonWasWithin('- 1 weeks')
            ->whereLastLessonWasAtLeast('- 3 days')
            ->hasNoFailedLessons()
            ->hasNoPendingLessons()
            ->hasNoConfirmedLessons()
            ->whereStatusNot('closed')
            ->whereStatusNot('rebookPlus');

        return $this->query->get();
    }

    public function getRebookStudents()
    {
        $this->newQuery()
            ->hasCompletedLessons(3)
            ->whereLastLessonWasWithin('- 1 weeks')
            ->whereLastLessonWasAtLeast('- 3 days')
            ->hasNoFailedLessons()
            ->hasNoPendingLessons()
            ->hasNoConfirmedLessons()
            ->whereStatusNot('closed')
            ->whereStatusNot('rebook');

        return $this->query->get();
    }

    

    public function getFirstLessonStudents()
    {
        $this->newQuery()
            ->hasCompletedLessons(1)
            ->whereLastLessonWasWithin('- 1 weeks')
            ->hasNoFailedLessons()
            ->whereStatusNot('closed')
            ->whereStatusNot('first');

        return $this->query->get();
    }

    public function getStudentNotRepliedStudents()
    {
        $this->newQuery()
            ->whereHasNoChattingRelationships()
            ->whereHasNoConfirmedRelationships()
            ->whereHasNoPendingRelationships()
            ->whereHasAStudentNotRepliedRelationship()
            ->whereCreatedWithin('-2 weeks')
            ->whereStatusNot('closed');

        return $this->query->get();
    }


    public function getFailedStudents()
    {
        $this->newQuery()
            ->hasFailedLessons();
            
        return $this->query->get();
    }

    ////////////////////////////////////////////////////////////////////////////////////////

    protected function whereStatusNot($status)
    {
        $this->query->where('status', '!=', $status);
        return $this;
    }

    protected function hasConfirmedLessons()
    {
        $this->query->whereIn('id', function($query) {
            $this->whereHasLessonStatus($query, LessonBooking::CONFIRMED);
        });
        return $this;
    }

    protected function hasNoConfirmedLessons()
    {
        $this->query->whereNotIn('id', function($query) {
            $this->whereHasLessonStatus($query, LessonBooking::CONFIRMED);
        });
        return $this;
    }


    protected function hasPendingLessons()
    {
        $this->query->whereIn('id', function($query) {
            $this->whereHasLessonStatus($query, LessonBooking::PENDING);
        });
        return $this;
    }

    protected function hasNoPendingLessons()
    {
        $this->query->whereNotIn('id', function($query) {
            $this->whereHasLessonStatus($query, LessonBooking::PENDING);
        });
        return $this;
    }

    protected function hasFailedLessons()
    {
        $this->query->whereIn('id', function($q) {
            $this->whereHasLessonChargeStatus($q, [LessonBooking::AUTHORISATION_FAILED, LessonBooking::PAYMENT_FAILED])
                ->where('lesson_bookings.payment_attempts', '>=', config('lessons.max_retry_payment_attempts'));
        });
        return $this;
    }

    protected function hasNoFailedLessons()
    {
       $this->query->whereNotIn('id', function($q) {
            $this->whereHasLessonChargeStatus($q, [LessonBooking::AUTHORISATION_FAILED, LessonBooking::PAYMENT_FAILED])                
                ->where('lesson_bookings.payment_attempts', '>=', config('lessons.max_retry_payment_attempts'));
        });
        return $this;
    }

    protected function hasCompletedLessons($lessonCount)
    {
        $this->query->whereIn('id', function($query) use ($lessonCount) {
            $query->select('users.id')
                ->from('lesson_bookings')
                ->groupBy('users.id')
                ->join('lessons', 'lessons.id', '=', 'lesson_bookings.lesson_id')
                ->join('relationships', 'relationships.id', '=', 'lessons.relationship_id')
                ->join('users', 'users.id', '=', 'relationships.student_id')
                ->where('lesson_bookings.status', '=', LessonBooking::COMPLETED)
                ->havingRaw("count(users.id) <= $lessonCount");
        });
        return $this;
    }

    protected function hasMoreThanOneCompletedLesson()
    {
        $this->query->whereIn('id', function($query) {
            $query->select('users.id')
                ->from('lesson_bookings')
                ->groupBy('users.id')
                ->join('lessons', 'lessons.id', '=', 'lesson_bookings.lesson_id')
                ->join('relationships', 'relationships.id', '=', 'lessons.relationship_id')
                ->join('users', 'users.id', '=', 'relationships.student_id')
                ->where('lesson_bookings.status', '=', LessonBooking::COMPLETED)
                ->havingRaw('count(users.id) > 1');
        });
        return $this;
    }

    protected function hasNoCompletedLessons()
    {
        $this->query->whereNotIn('id', function($query) {
            $this->whereHasLessonStatus($query, LessonBooking::COMPLETED);
        });
        return $this;
    }

    protected function hasLessonSchedule()
    {
        $this->query->whereIn('id', function($q) {
                $q->select('users.id')
                ->from('lesson_schedules')
                ->join('lessons', 'lessons.id', '=', 'lesson_schedules.lesson_id')
                ->join('relationships', 'relationships.id', '=', 'lessons.relationship_id')
                ->join('users', 'users.id', '=', 'relationships.student_id');
            });
        return $this;
    }

    protected function hasNoLessonSchedule()
    {
        $this->query->whereNotIn('id', function($q) {
                $q->select('users.id')
                ->from('lesson_schedules')
                ->join('lessons', 'lessons.id', '=', 'lesson_schedules.lesson_id')
                ->join('relationships', 'relationships.id', '=', 'lessons.relationship_id')
                ->join('users', 'users.id', '=', 'relationships.student_id');
            });
        return $this;
    }

    protected function hasNoRelationships()
    {
        $this->query
            ->whereNotIn('id', function($q) {
                    $q->select('users.id')
                    ->from('messages')
                    ->join('relationships', 'relationships.id', '=', 'messages.relationship_id')
                    ->join('users', 'users.id', '=', 'relationships.student_id');
            });
        return $this;
    }

    protected function whereLastLessonWasWithin($period)
    {
        $date = Carbon::now()->modify($period);

        $this->query->whereIn('id', function($query) use ($date) {
            $query->select('users.id')
                ->from('lesson_bookings')
                ->groupBy('users.id')
                ->join('lessons', 'lessons.id', '=', 'lesson_bookings.lesson_id')
                ->join('relationships', 'relationships.id', '=', 'lessons.relationship_id')
                ->join('users', 'users.id', '=', 'relationships.student_id')
                ->where('lesson_bookings.status', '=', LessonBooking::COMPLETED)
                ->where('lesson_bookings.finish_at', '>', $date)
                ->havingRaw('count(users.id) = 1');
        });
        return $this;

    }

    protected function whereLastLessonWasAtLeast($period)
    {
        $date = Carbon::now()->modify($period);

        $this->query->whereIn('id', function($query) use ($date) {
            $query->select('users.id')
                ->from('lesson_bookings')
                ->groupBy('users.id')
                ->join('lessons', 'lessons.id', '=', 'lesson_bookings.lesson_id')
                ->join('relationships', 'relationships.id', '=', 'lessons.relationship_id')
                ->join('users', 'users.id', '=', 'relationships.student_id')
                ->where('lesson_bookings.status', '=', LessonBooking::COMPLETED)
                ->where('lesson_bookings.finish_at', '<', $date);
        });
        return $this;

    }

    protected function whereHasNoChattingRelationships()
    {
        $this->query
            ->whereNotIn('id', function($q) {
                    $q->select('users.id')
                    ->from('relationships')
                    ->join('users', 'users.id', '=', 'relationships.student_id')
                    ->where('relationships.status', '=', Relationship::CHATTING);
            });
        return $this;
    }

    protected function whereHasNoConfirmedRelationships()
    {
        $this->query
            ->whereNotIn('id', function($q) {
                    $q->select('users.id')
                    ->from('relationships')
                    ->join('users', 'users.id', '=', 'relationships.student_id')
                    ->where('relationships.status', '=', Relationship::CONFIRMED);
            });
        return $this;
    }

        protected function whereHasNoPendingRelationships()
    {
        $this->query
            ->whereNotIn('id', function($q) {
                    $q->select('users.id')
                    ->from('relationships')
                    ->join('users', 'users.id', '=', 'relationships.student_id')
                    ->where('relationships.status', '=', Relationship::PENDING);
            });
        return $this;
    }

    protected function whereHasNoNoReplyRelationships()
    {
        $this->query
            ->whereNotIn('id', function($q) {
                    $q->select('users.id')
                    ->from('relationships')
                    ->join('users', 'users.id', '=', 'relationships.student_id')
                    ->where('relationships.status', '=', Relationship::NO_REPLY);
            });
        return $this;
    }

    protected function whereHasNoMismatchedRelationships()
    {
        $this->query
            ->whereNotIn('id', function($q) {
                    $q->select('users.id')
                    ->from('relationships')
                    ->join('users', 'users.id', '=', 'relationships.student_id')
                    ->where('relationships.status', '=', Relationship::MISMATCHED);
            });
        return $this;
    }

    protected function whereHasAStudentNotRepliedRelationship()
    {
        $this->query
            ->whereIn('id', function($q) {
                    $q->select('users.id')
                    ->from('relationships')
                    ->join('users', 'users.id', '=', 'relationships.student_id')
                    ->where('relationships.status', '=', Relationship::NO_REPLY_STUDENT);
            });
        return $this;
    }

    protected function whereHasLessonStatus($query, $status)
    {
        $query->select('users.id')
                ->from('lesson_bookings')
                ->join('lessons', 'lessons.id', '=', 'lesson_bookings.lesson_id')
                ->join('relationships', 'relationships.id', '=', 'lessons.relationship_id')
                ->join('users', 'users.id', '=', 'relationships.student_id')
                ->where('lesson_bookings.status', '=', $status);
                
        return $query;
    }

    protected function whereHasLessonChargeStatus($query, $status)
    {
        if (is_array($status)) {

            $query->select('users.id')
                    ->from('lesson_bookings')
                    ->join('lessons', 'lessons.id', '=', 'lesson_bookings.lesson_id')
                    ->join('relationships', 'relationships.id', '=', 'lessons.relationship_id')
                    ->join('users', 'users.id', '=', 'relationships.student_id')
                    ->whereIn('lesson_bookings.charge_status', $status);
                    
            return $query;
        }

        $query->select('users.id')
                ->from('lesson_bookings')
                ->join('lessons', 'lessons.id', '=', 'lesson_bookings.lesson_id')
                ->join('relationships', 'relationships.id', '=', 'lessons.relationship_id')
                ->join('users', 'users.id', '=', 'relationships.student_id')
                ->where('lesson_bookings.charge_status', '=', $status);
                
        return $query;
    }

    protected function whereCreatedWithin($period)
    {
        $date = Carbon::now()->modify($period);
        $this->query->where('users.created_at', '>', $date);
        return $this;

    }


    protected function newQuery()
    {
        $this->query  = $this->student->newQuery();
        return $this;
    }

   

}

<?php namespace App\Repositories;

use App\Repositories\Contracts\LessonRepositoryInterface;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Support\Collection;
use App\LessonSchedule;
use App\LessonBooking;
use App\Lesson;
use App\Tutor;
use App\Student;
use App\User;

class EloquentLessonRepository implements LessonRepositoryInterface
{

    /*
     * @var Database
     */
    protected $database;

    /*
     * @var Lesson
     */
    protected $lesson;

    /**
     * Create an instance of this
     *
     * @param Database $databse
     * @return void
     */
    public function __construct(Database $database, Lesson $lesson)
    {
        $this->database = $database;
        $this->lesson   = $lesson;
    }

    /**
     * Persist a lesson to the database
     *
     * @param  Lesson            $lesson
     * @param  Array|ArrayAccess $bookings
     * @param  LessonSchedule    $schedule
     * @return Lesson|null
     */
    public function save(
        Lesson         $lesson,
        /* Array */    $bookings = null,
        LessonSchedule $schedule = null
    ) {
        guard_against_array_of_invalid_arguments($bookings, LessonBooking::class);

        return $this->database->transaction(function () use (
            $lesson,
            $bookings,
            $schedule
        ) {
            // Lesson
            if ( ! $lesson->save()) {
                throw new ResourceNotPersistedException();
            }

            // Bookings
            if ($bookings !== null) {
                if ($bookings instanceof Collection) {
                    foreach ($bookings as $booking) {
                        if ( ! $lesson->bookings()->save($booking)) {
                            throw new ResourceNotPersistedException();
                        }
                    }
                } else if ( ! $lesson->bookings()->saveMany($bookings)) {
                    throw new ResourceNotPersistedException();
                }
            }

            // Schedule
            if ($schedule !== null) {
                if ( ! $lesson->schedule()->save($schedule)) {
                    throw new ResourceNotPersistedException();
                }
            }

            return $lesson;
        });
    }

    /**
     * Find a lesson by id.
     *
     * @param  Integer $id
     * @return Lesson
     */
    public function findById($id)
    {
        return $this->lesson
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Find all lessons by a given tutor.
     *
     * @param  User $tutor
     * @return Collection
     */
    public function findByTutor(User $tutor)
    {
        return $user->lessons()
            ->select(
                '*',
                $this->database->raw('((`start_at` - NOW()) < 0) as `has_passed`')
            )
            ->orderBy('has_passed')
            ->orderBy(
                $this->database->raw('ABS(`start_at` - NOW())')
            )
            ->get();
    }

    public function getByTutorAndStudent(Tutor $tutor, Student $student)
    {
        return $this->lesson
            ->newQuery()
            ->whereHas('tutor', function ($query) use ($tutor) {
                return $query->where('tutor_id', '=', $tutor->id);
            })
            ->whereHas('student', function ($query) use ($student) {
                return $query->where('student_id', '=', $student->id);
            })
            ->get();
    }

}

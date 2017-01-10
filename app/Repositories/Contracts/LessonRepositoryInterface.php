<?php namespace App\Repositories\Contracts;

use App\LessonSchedule;
use App\LessonBooking;
use App\Lesson;
use App\User;

interface LessonRepositoryInterface
{

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
    );

    /**
     * Find a lesson by id.
     *
     * @param  Integer $id
     * @return Lesson
     */
    public function findById($id);

    /**
     * Find all lessons by a given tutor.
     *
     * @param User $user
     * @return Collection
     */
    public function findByTutor(User $user);

}

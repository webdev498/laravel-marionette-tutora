<?php namespace App\Repositories\Contracts;

use App\Tutor;
use App\LessonBooking;
use App\Relationship;

interface LessonBookingRepositoryInterface
{

    /**
     * Persist a lesson booking to the database
     *
     * @param  LessonBooking $booking
     * @return LessonBooking|null
     */
    public function save(LessonBooking $booking);

    /**
     * Find a lesson booking by id.
     *
     * @param  Integer $id
     * @return LessonBooking|null
     */
    public function findById($id);

    /**
     * Find a booking by a given uuid
     *
     * @param  string $uuid
     * @return LessonBooking|null
     */
    public function findByUuid($uuid);

    /**
     * Find all lessons by a given tutor.
     *
     * @param  User $tutor
     * @return Collection
     */
    public function getByTutor(Tutor $tutor, $page, $perPage);

    /**
     * Count the number of bookings a given tutor has.
     *
     * @param  User $tutor
     * @return integer
     */
    public function countByTutor(Tutor $tutor);

    /**
     * Get all of the lessons by a given relationship and status
     *
     * @param  Relationship $relationship
     * @param  mixed        $start
     * @param  mixed        $end
     *
     * @return integer
     */
    public function countByRelationshipAndStatus(Relationship $relationship, $status, $start = null, $end = null);

}

<?php namespace App\Repositories\Contracts;

use App\User;
use App\Role;
use App\Message;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{

    /**
     * @param User $user
     * @return void
     */
    public function __construct(User $user);

    /**
     * Save a given user
     *
     * @param  User $user
     * @return User
     */
    public function save(User $user);

    /**
     * Find a user by uuid
     *
     * @param String $uuid
     * @return User|null
     */
    public function findByUuid($uuid);

    /**
     * Get multiple users by given ids
     *
     * @param Array $ids
     * @return Collection
     */
    public function getByIds(array $ids);

    /**
     * Return the number of users that have a given id
     *
     * @param Integer $id
     * @return Integer
     */
    public function countById($id);

    /**
     * Get multiple users by given uuids
     *
     * @param Array $uuids
     * @return Collection
     */
    public function getByUuids(array $uuids);


    /**
     * Return the number of users that have a given uuid
     *
     * @param  String $uuid
     * @return Integer
     */
    public function countByUuid($uuid);

    /**
     * Return the number of users that have a given email
     *
     * @param string $email
     * @return integer
     */
    public function countByEmail($email);

    /**
     * Return the users that belong to a message, who are tutors.
     *
     * @param  Message $message
     * @return Collection
     */
    public function getTutorsByMessage(Message $message);

    /**
     * Return the users that belong to a message, who are students.
     *
     * @param  Message $message
     * @return Collection
     */
    public function getStudentsByMessage(Message $message);

    /**
     * Attach a number of students to a tutor
     *
     * @param  User       $tutor
     * @param  Collection $students
     * @return void
     */
    public function attachStudentsToTutor(User $tutor, Collection $students);

}

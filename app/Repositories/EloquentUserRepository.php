<?php

namespace App\Repositories;

use App\User;
use App\Role;
use App\Message;
use App\Relationship;
use Illuminate\Support\Collection;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Database\Exceptions\ResourceNotPersistedException;

class EloquentUserRepository extends AbstractEloquentRepository implements
    UserRepositoryInterface
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Save a given user
     *
     * @param User $user
     * @throws ResourceNotPersistedException
     * @return User
     */
    public function save(User $user)
    {
        if ( ! $user->push()) {
            throw new ResourceNotPersistedException();
        }

        return $user;
    }

    /**
     * Find a user by id
     *
     * @param  Integer $id
     * @return User|null
     */
    public function findById($id)
    {
        return $this->user
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Find a user by uuid
     *
     * @param  string $uuid
     * @return User|null
     */
    public function findByUuid($uuid)
    {
        return $this->user
            ->with($this->with)
            ->where('uuid', '=', $uuid)
            ->first();
    }

    /**
     * Find a user by a given uuid, or fail
     *
     * @param  string $uuid
     * @return User
     * @throws ResourceNotFoundException
     */
    public function findByUuidOrFail($uuid)
    {
        // Lookup
        $user = $this->findByUuid($uuid);
        // Throw
        if ( ! $user) {
            throw new ResourceNotFoundException();
        }
        // Return
        return $user;
    }

    /**
     * Find a user by a given email
     *
     * @param  string $email
     * @return User|null
     */
    public function findByEmail($email)
    {
        return $this->user
            ->where('email', '=', $email)
            ->first();
    }

    /**
     * Find a user by a given billing id
     *
     * @param  string $id
     * @return User|null
     */
    public function findByBillingId($id)
    {
        return $this->user
            ->where('billing_id', '=', $id)
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
        return $this->user
           ->whereIn('id', $ids)
           ->get();
    }

    /**
     * Return the number of users that have a given id
     *
     * @param Integer $id
     * @return Integer
     */
    public function countById($id)
    {
        return $this->user
            ->whereId($id)
            ->count();
    }

    /**
     * Get multiple users by given uuids
     *
     * @param Array $uuids
     * @return Collection
     */
    public function getByUuids(array $uuids)
    {
        return $this->user
            ->newQuery()
            ->whereIn('uuid', $uuids)
            ->get();
    }

    /**
     * Return the number of users that have a given uuid
     *
     * @param string $uuid
     * @return integer
     */
    public function countByUuid($uuid)
    {
        return $this->user
            ->whereUuid($uuid)
            ->count();
    }

    /**
     * Return the number of users that have a given email
     *
     * @param string $email
     * @return integer
     */
    public function countByEmail($email)
    {
        return $this->user
            ->whereEmail($email)
            ->count();
    }

    /**
     * Get the students by searching the given columns for a given query.
     *
     * @param   string $query
     * @param   $columns
     * @return  Collection
     */
    public function getByQuery($terms)
    {
        
        $terms = explode (' ' , $terms);

        $results = \DB::table('users')
            ->select('*')
            ->where(function($query) use ($terms) {
            
                foreach ($terms as $term) {
                    $query = $query->where(function($q) use ($term) {
                        $q->whereRaw("CONCAT(first_name, ' ', last_name, ' ', REPLACE(`telephone`,' ',''), ' ', email) LIKE '%$term%' COLLATE utf8_general_ci");
                    });
                }                
            })
            ->get();

        return $this->user->hydrate($results);
    }


    /**
     * Return the users that belong to a message, who are tutors.
     *
     * @param  Message $message
     * @return Collection
     */
    public function getTutorsByMessage(Message $message)
    {
        return $this->user
            ->whereHas('messages', function ($query) use ($message) {
                return $query
                    ->where('messages.id', '=', $message->id);
            })
            ->whereHas('roles', function ($query) {
                return $query
                    ->where('roles.name', '=', Role::TUTOR);
            })
            ->get();
    }

    /**
     * Return the users that belong to a message, who are students.
     *
     * @param  Message $message
     * @return Collection
     */
    public function getStudentsByMessage(Message $message)
    {
        return $this->user
            ->whereHas('messages', function ($query) use ($message) {
                return $query
                    ->where('messages.id', '=', $message->id);
            })
            ->whereHas('roles', function ($query) {
                return $query
                    ->where('roles.name', '=', Role::STUDENT);
            })
            ->get();
    }

    /**
     * Attach a number of students to a tutor
     *
     * @param  User       $tutor
     * @param  Collection $students
     * @return void
     */
    public function attachStudentsToTutor(User $tutor, Collection $students)
    {
        $students = $students->diff($tutor->students);

        foreach ($students as $student)  {
            $relationship = new Relationship();

            $relationship->tutor_id   = $tutor->id;
            $relationship->student_id = $student->id;

            $relationship->save();
        }
    }

}

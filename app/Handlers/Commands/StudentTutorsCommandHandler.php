<?php

namespace App\Handlers\Commands;

use App\Student;
use App\User;
use App\Tutor;
use App\Job;
use App\Commands\StudentTutorsCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Auth\Exceptions\UnauthorizedException;
use Illuminate\Support\Collection;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Search\Exceptions\NotFoundException;
use App\Repositories\Contracts\TutorRepositoryInterface;

class StudentTutorsCommandHandler extends CommandHandler
{
    /**
     * An array of objects to dispatch events off.
     *
     * @var array
     */
    protected $dispatch = [];

    /**
     * @var Database
     */
    protected $database;

    /**
     * The Guard implementation.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * TutorRepositoryInterface
     */
    protected $tutors;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                 $database
     * @param  Auth                     $auth
     * @param  UserRepositoryInterface  $users
     * @param  TutorRepositoryInterface $tutors
     */
    public function __construct(
        Database                 $database,
        Auth                     $auth,
        UserRepositoryInterface  $users,
        TutorRepositoryInterface $tutors
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->users     = $users;
        $this->tutors    = $tutors;
    }

    /**
     * @param StudentTutorsCommand $command
     *
     * @return Collection
     *
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function handle(StudentTutorsCommand $command)
    {
        // Guard
        $currentUser = $this->auth->user();
        $this->guardAgainstUserNotAllowedToGet($currentUser);

        $user = $this->users->findByUuid($command->uuid);
        if(!$user || !$user->isStudent()) {
            throw new NotFoundException();
        }

        $tutors = $this->findTutors($user);

        // Return
        return [$tutors, $user];
    }

    /**
     * Guard against a user not having permission to get a user.
     *
     * @param  User $user
     * @return void
     *
     * @throws UnauthorizedException
     */
    protected function guardAgainstUserNotAllowedToGet(User $user)
    {
        if (!$user->isAdmin()) {
            throw new UnauthorizedException();
        }
    }

    /**
     * @param Student $student
     *
     * @return Collection
     */
    protected function findTutors(Student $student)
    {
        $items = $this->tutors
            ->with([
                "reviews:by({$student->id})",
                'relationships',
                'relationships.message',
                'relationships.lessons:next',
                'relationships.lessons.subject',
                'relationships.lessons.bookings:next',
            ])
            ->getByStudent($student);

        return $items;
    }

    /**
     * Find a job by a given uuid
     *
     * @throws ResourceNotFoundException
     * @param  string $uuid
     *
     * @return Job
     */
    protected function findJob($uuid)
    {
        $job = $this->jobs->findByUuid($uuid);

        if (!$job) {
            throw new ResourceNotFoundException();
        }

        return $job;
    }

}

<?php namespace App\Handlers\Commands;

use App\Tutor;
use App\Validators\SubjectsValidator;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Commands\TutorSubjectsUpdateCommand;
use App\Auth\Exceptions\UnauthorizedException;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\SubjectRepositoryInterface;

class TutorSubjectsUpdateCommandHandler extends CommandHandler
{

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
     * @var SubjectsValidator
     */
    protected $validator;

    /**
     * @var SubjectRepositoryInterface
     */
    protected $subjects;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                   $databaes
     * @param  Auth                       $auth
     * @param  SubjectsValidator          $validator
     * @param  SubjectRepositoryInterface $subjects
     * @param  UserRepositoryInterface    $users
     * @return void
     */
    public function __construct(
        Database                   $database,
        Auth                       $auth,
        SubjectsValidator          $validator,
        SubjectRepositoryInterface $subjects,
        UserRepositoryInterface    $users
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->validator = $validator;
        $this->subjects  = $subjects;
        $this->users     = $users;
    }

    /**
     * Execute the command.
     *
     * @param  TutorSubjectsUpdateCommand $command
     * @return Subjects
     */
    public function handle(TutorSubjectsUpdateCommand $command)
    {
        return $this->database->transaction(function() use ($command) {
            // Validate
            $this->guardAgainstInvalidData($command);
            // Lookups
            $tutor    = $this->findUser($command->uuid);
            $subjects = $this->findSubjects($command->subjects);
            // Guard
            $this->guardAgainstUnauthorized($tutor);
            // Sync
            $this->subjects->syncWithTutor($tutor, $subjects);
            // Dispatch
            $this->dispatchFor($this->subjects);
            // Return
            return $subjects;
        });
    }

    /**
     * Find a user by either a given User, array or string.
     *
     * @param  mixed $user App\User, array w/ a uuid key or a string uuid.
     * @return User
     */
    protected function findUser($user)
    {
        if ( ! ($user instanceof User)) {
            $uuid = is_array($user)
                ? array_get($user, 'uuid')
                : $user;

            $user = $this->users->findByUuid($uuid);

            if ( ! $user) {
                throw new ResourceNotFoundException();
            }
        }

        return $user;
    }

    /**
     * Find the subjects by a given array /w id keys
     *
     * @param  Array $subjects
     * @return Collection
     */
    protected function findSubjects(Array $subjects)
    {
        return $this->subjects->getByIds(
            array_pluck($subjects, 'id')
        );
    }

    /**
     * Guard against invalid data on the command
     *
     * @throws ValidationException
     * @param  TutorSubjectsUpdateCommand $command
     * @return void
     */
    public function guardAgainstInvalidData(TutorSubjectsUpdateCommand $command)
    {
        return $this->validator->validate((array) $command);
    }

    /**
     * Guard against unauthorised editing of this user
     *
     * @throws UnauthorizedException
     * @param  Tutor $tutor
     * @return void
     */
    protected function guardAgainstUnauthorized(Tutor $user)
    {
        $authed = $this->auth->user();
        if ( ! $authed && $authed->id !== $user->id) {
            throw new UnauthorizedException();
        }
    }

}

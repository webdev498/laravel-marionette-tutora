<?php namespace App\Handlers\Commands;

use App\Tutor;
use App\Commands\QTSUpdateCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\UserQualificationTeacherStatus as QTS;
use Illuminate\Database\DatabaseManager as Database;
use App\Validators\QualificationTeacherStatusValidator;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\QualificationRepositoryInterface;

class QTSUpdateCommandHandler extends CommandHandler
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
     * @var QualificationsValidator
     */
    protected $validator;

    /**
     * @var QualificationRepositoryInterface
     */
    protected $qualifications;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * Create the command handler.
     *
     * @param  Database                            $databaes
     * @param  Auth                                $auth
     * @param  QualificationTeacherStatusValidator $validator
     * @param  QualificationRepositoryInterface    $qualifications
     * @param  UserRepositoryInterface             $users
     * @return void
     */
    public function __construct(
        Database                            $database,
        Auth                                $auth,
        QualificationTeacherStatusValidator $validator,
        QualificationRepositoryInterface    $qualifications,
        UserRepositoryInterface             $users
    ) {
        $this->database       = $database;
        $this->auth           = $auth;
        $this->validator      = $validator;
        $this->qualifications = $qualifications;
        $this->users          = $users;
    }

    /**
     * Handle the command.
     *
     * @param  QTSUpdateCommand  $command
     * @return void
     */
    public function handle(QTSUpdateCommand $command)
    {
        return $this->database->transaction(function() use ($command) {
            // Validate
            $this->guardAgainstInvalidData($command);
            // Lookups
            $tutor = $this->findUser($command->uuid);
            // Guard
            $this->guardAgainstUnauthorized($tutor);
            // Edit
            $qts = QTS::attend($command->level);
            // Sync
            $this->qualifications->syncQtsWithTutor($tutor, $qts);
            // Dispatch
            $this->dispatchFor($this->qualifications);
            // Return
            return $qts;
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
     * Guard against invalid data on the command
     *
     * @throws ValidationException
     * @param  QTSUpdateCommand $command
     * @return void
     */
    public function guardAgainstInvalidData(QTSUpdateCommand $command)
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

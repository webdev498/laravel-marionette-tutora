<?php namespace App\Handlers\Commands;

use App\Job;
use App\Role;
use App\User;
use App\Tutor;
use App\Admin;
use App\Student;
use App\Commands\Jobs\CreateJobCommand;
use App\Commands\RegisterUserCommand;
use App\Validators\RegisterUserValidator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Database\Exceptions\DuplicateResourceException;
use Illuminate\Auth\AuthManager as Auth;

class RegisterUserCommandHandler extends CommandHandler
{

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var RegisterUserValidator
     */
    protected $validator;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var RoleRepositoryInterface
     */
    protected $roles;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * Create an instance of the handler.
     *
     * @param Database                $database
     * @param RegisterUserValidator   $validator
     * @param UserRepositoryInterface $users
     * @param RoleRepositoryInterface $roles
     * @param Auth                    $auth
     */
    public function __construct(
        Database                $database,
        RegisterUserValidator   $validator,
        UserRepositoryInterface $users,
        RoleRepositoryInterface $roles,
        Auth                    $auth
    ) {
        $this->database  = $database;
        $this->validator = $validator;
        $this->users     = $users;
        $this->roles     = $roles;
        $this->auth      = $auth;
    }

    /**
     * Execute the command.
     *
     * @param  RegisterUserCommand $command
     * @return User
     */
    public function handle(RegisterUserCommand $command)
    {
        return $this->database->transaction(function () use ($command) {

            $this->guardAgainstInvalidData($command);
            $this->guardAgainstDuplicateEmail($command->email);

            $uuid = $this->generateUuid();

            $role = $this->roles->findByName($command->account);
            
            if ($role->name === Role::TUTOR) {
                $user = $this->registerTutor($uuid, $command);
            } else {
                $user = $this->registerStudent($uuid, $command);
            }

            $this->users->save($user);

            $this->roles->saveForUser($user, $role);

            $this->dispatchFor($user);

            return $user;
        });
    }

    /**
     * @param $uuid
     * @param RegisterUserCommand $command
     *
     * @return User
     */
    protected function registerTutor($uuid, RegisterUserCommand $command)
    {
        $user = Tutor::register(
            $uuid,
            $command->first_name,
            $command->last_name,
            $command->email,
            $command->telephone,
            $command->password
        );

        return $user;
    }

    /**
     * @param $uuid
     * @param RegisterUserCommand $command
     *
     * @return User
     */
    protected function registerStudent($uuid, RegisterUserCommand $command)
    {
        $user = Student::register(
            $uuid,
            $command->first_name,
            $command->last_name,
            $command->email,
            $command->telephone,
            $command->password
        );

        return $user;
    }

    /**
     * Generate a uuid, ensuring it is infact unique to the user
     *
     * @return string
     */
    protected function generateUuid()
    {
        do {
            $uuid = str_uuid();
        } while ($this->users->countByUuid($uuid) > 0);

        return $uuid;
    }

    /**
     * Guard against invalid data on the command
     *
     * @throws ValidationException
     * @return void
     */
    protected function guardAgainstInvalidData($command)
    {
        $this->validator->validate((array) $command);
    }

    /**
     * Guard against using an existing email
     *
     * @throws DuplicateResourceException
     *
     * @return void
     */
    protected function guardAgainstDuplicateEmail($email)
    {
        if ($this->users->countByEmail($email) > 0) {
            throw new DuplicateResourceException([
                [
                    'field'  => 'email',
                    'detail' => trans('validation.unique', ['attribute' => 'email'])
                ]
            ]);
        }
    }

}

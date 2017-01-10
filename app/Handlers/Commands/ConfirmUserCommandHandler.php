<?php namespace App\Handlers\Commands;

use App\User;
use App\Commands\ConfirmUserCommand;
use Illuminate\Database\DatabaseManager;
use App\Validators\ConfirmUserValidator;
use App\Repositories\Contracts\UserRepositoryInterface;

class ConfirmUserCommandHandler extends CommandHandler
{

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var ConfirmUserValidator
     */
    protected $validator;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /*
     * Create the command handler.
     *
     * @param  DatabaseManager         $database
     * @param  ConfirmUserValidator    $validator
     * @param  UserRepositoryInterface $users
     * @return void
     */
    public function __construct(
        DatabaseManager         $database,
        ConfirmUserValidator    $validator,
        UserRepositoryInterface $users
    ) {
        $this->database  = $database;
        $this->validator = $validator;
        $this->users     = $users;
    }

    /**
     * Handle the command.
     *
     * @param  ConfirmUserCommand  $command
     * @return void
     */
    public function handle(ConfirmUserCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            $this->guardAgainstInvalidData($command);

            $user = $this->users->findByUuid($command->uuid);

            if ($user->confirmation_token !== $command->token) {
                return false;
            }

            $user = User::confirmRegistration($user);

            $this->users->save($user);

            return true;
        });
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

}

<?php namespace App\Handlers\Commands;

use App\Auth\Exceptions\UnauthorizedException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\User;
use Illuminate\Database\DatabaseManager;
use App\Commands\ToggleBlockUserCommand;
use Illuminate\Contracts\Auth\Guard as Auth;

class ToggleBlockUserCommandHandler extends CommandHandler
{

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @param DatabaseManager $database
     * @param Auth $auth
     * @param UserRepositoryInterface $users
     */
    public function __construct(
        DatabaseManager                   $database,
        Auth                              $auth,
        UserRepositoryInterface           $users
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->users     = $users;
    }

    /**
     * Handle the command.
     *
     * @param  ToggleBlockUserCommand  $command
     * @return $tutor
     */
    public function handle(ToggleBlockUserCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            $this->guardAgainstUserNotPermitted();

            $user = $this->findUser($command->uuid);

            // Set tutor to deleted
            $user = User::toggleBlocked($user);

            $user->save();

            $this->dispatchFor($user);

            return $user;
        });
    }

    /**
     * @param $uuid
     * @return mixed
     * @throws UserNotFoundException
     */
    protected function findUser($uuid)
    {
        $user = $this->users->findByUuid($uuid);

        if ( ! $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param User $user
     * @throws UnauthorizedException
     */
    protected function guardAgainstUserNotPermitted()
    {
        if ( ! $this->auth->user()->isAdmin()) {
            throw new UnauthorizedException();
        }
    }

}

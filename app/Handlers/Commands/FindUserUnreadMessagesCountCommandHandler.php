<?php

namespace App\Handlers\Commands;

use App\Auth\Exceptions\UnauthorizedException;
use App\Commands\FindUserUnreadMessagesCountCommand;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Handlers\Commands\CommandHandler;
use App\MessageStatus;
use App\Tutor;
use App\User;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Support\Collection;

class FindUserUnreadMessagesCountCommandHandler extends CommandHandler
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
     * Create an instance of the handler.
     *
     * @param  Database      $database
     * @param  Auth          $auth
     */
    public function __construct(
        Database      $database,
        Auth          $auth
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
    }

    /**
     * Execute the command.
     *
     * @param FindUserUnreadMessagesCountCommand $command
     *
     * @return int
     */
    public function handle(FindUserUnreadMessagesCountCommand $command)
    {
        // Guard
        $currentUser = $this->auth->user();
        $this->guardAgainstUserNotAllowedToGet($currentUser);

        $count = $this->findUnreadMessagesCount($command);

        // Return
        return $count;
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
        $isTutor = $user->roles();

        if (!$user->isAdmin() && !$isTutor) {
            throw new UnauthorizedException();
        }
    }

    /**
     * @param FindUserUnreadMessagesCountCommand $command
     *
     * @return int
     */
    protected function findUnreadMessagesCount(FindUserUnreadMessagesCountCommand $command)
    {
        $user = $command->user;

        $count = MessageStatus::where('user_id', $user->id)->where('unread', 1)->count();

        return $count;
    }
}

<?php

namespace App\Handlers\Commands\BackgroundChecks;

use App\User;
use App\Admin;
use App\Tutor;
use App\Image;
use App\UserBackgroundCheck;
use App\Handlers\Commands\CommandHandler;
use App\Commands\BackgroundChecks\DeleteTutorBackgroundCheckCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\BackgroundCheckRepositoryInterface;
use App\Auth\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Events\BackgroundCheckWasRemoved;

class DeleteTutorBackgroundCheckCommandHandler extends CommandHandler
{
    /**
     * @var Database
     */
    protected $database;

    /**
     * @var BackgroundCheckRepositoryInterface
     */
    protected $backgroundChecks;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * Create a new handler instance.
     *
     * @param  Database                             $database
     * @param  BackgroundCheckRepositoryInterface   $backgroundChecks
     * @param  UserRepositoryInterface              $users
     * @param  Auth                                 $auth
     */
    public function __construct(
        Database                            $database,
        BackgroundCheckRepositoryInterface  $backgroundChecks,
        UserRepositoryInterface             $users,
        Auth                                $auth
    ) {
        $this->database         = $database;
        $this->backgroundChecks = $backgroundChecks;
        $this->users            = $users;
        $this->auth             = $auth;
    }

    /**
     * Execute the command.
     *
     * @param  DeleteTutorBackgroundCheckCommand $command
     */
    public function handle(DeleteTutorBackgroundCheckCommand $command)
    {
        return $this->database->transaction(function () use ($command) {

            $type = $command->type == 'dbs' ? UserBackgroundCheck::TYPE_DBS_CHECK : UserBackgroundCheck::TYPE_DBS_UPDATE;

            $owner = $this->users->findByUuid($command->uuid);
            $background = $this->backgroundChecks->getUserBackgroundCheckByType($owner, $type);

            $this->guardAgainstUnauthorized($background);

            $this->deleteBackground($background, $command);

            $this->dispatch(new BackgroundCheckWasRemoved($owner, $command->uuid));
        });
    }

    /**
     * @param UserBackgroundCheck               $background
     * @param DeleteTutorBackgroundCheckCommand $command
     *
     * @return boolean
     */
    protected function deleteBackground(UserBackgroundCheck $background, DeleteTutorBackgroundCheckCommand $command)
    {
        return $this->backgroundChecks->delete($background);
    }

    /**
     * Guard against unauthorised request
     *
     * @throws UnauthorizedException
     * @param  UserBackgroundCheck $background
     *
     * @return void
     */
    protected function guardAgainstUnauthorized(UserBackgroundCheck $background)
    {
        $authed = $this->auth->user();

        $isAdmin = $authed->isAdmin();

        if (!$isAdmin) {
            throw new UnauthorizedException();
        }
    }

}

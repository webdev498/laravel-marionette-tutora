<?php

namespace App\Handlers\Commands;

use App\User;
use App\Admin;
use App\Tutor;
use App\Address;
use App\UserProfile;
use App\IdentityDocument;
use App\Commands\GetUserCommand;
use App\Validators\UpdateUserValidator;
use App\Billing\Contracts\BillingInterface;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Database\Exceptions\DuplicateResourceException;
use App\Auth\Exceptions\UnauthorizedException;

class GetUserCommandHandler extends CommandHandler
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
     * @var UpdateUserValidator
     */
    protected $validator;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var BillingInterface
     */
    protected $billing;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                $databaes
     * @param  Auth                    $auth
     * @param  UpdateUserValidator     $validator
     * @param  UserRepositoryInterface $users
     * @param  BillingInterface        $billing
     * @return void
     */
    public function __construct(
        Database                $database,
        Auth                    $auth,
        UpdateUserValidator     $validator,
        UserRepositoryInterface $users,
        BillingInterface        $billing
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->validator = $validator;
        $this->users     = $users;
        $this->billing   = $billing;
    }

    /**
     * Execute the command.
     *
     * @param  GetUserCommand $command
     * @return User
     */
    public function handle(GetUserCommand $command)
    {

        return $this->database->transaction(function () use ($command) {

            // Guard
            $currentUser = $this->auth->user();
            $this->guardAgainstUserNotAllowedToGet($currentUser);

            $user = $this->findUser($command->uuid);

            $this->dispatch[] = $user;

            // Dispatch
            $this->dispatchFor($this->dispatch);

            // Return
            return $user;
        });
    }

    /**
     * Find a user by a given uuid
     *
     * @throws ResourceNotFoundException
     * @param  string $uuid
     * @return User
     */
    protected function findUser($uuid)
    {
        $user = $this->users->findByUuid($uuid);

        if ( ! $user) {
            throw new ResourceNotFoundException();
        }

        return $user;
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
        if (! $user->isAdmin()) {
            throw new UnauthorizedException();
        }
    }

}

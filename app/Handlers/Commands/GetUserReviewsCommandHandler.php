<?php

namespace App\Handlers\Commands;

use App\Repositories\Contracts\TutorRepositoryInterface;
use App\User;
use App\Commands\GetUserReviewsCommand;
use App\Billing\Contracts\BillingInterface;
use App\UserReview;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Database\Exceptions\DuplicateResourceException;
use App\Auth\Exceptions\UnauthorizedException;

class GetUserReviewsCommandHandler extends CommandHandler
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
    protected $tutors;

    /**
     * @var BillingInterface
     */
    protected $billing;


    public function __construct(
        Database                $database,
        TutorRepositoryInterface       $users,
        Auth                    $auth
    ) {
        $this->database  = $database;
        $this->users     = $users;
        $this->auth      = $auth;
    }

    /**
     * Execute the command.
     *
     * @param  GetUserCommand $command
     * @return User
     */
    public function handle(GetUserReviewsCommand $command)
    {

        return $this->database->transaction(function () use ($command) {

            // Guard
            $user = $this->auth->user();
            $this->guardAgainstUserNotAllowedToGet($user);

            $tutor = $this->users->findByUuid($command->uuid);

            if ($command->deleted === 'false') {
                $reviews = $tutor->reviews()->get();
            } else {
                $reviews = $tutor->reviews()->onlyTrashed()->get();
            }

            // Return
            return $reviews;
        });
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

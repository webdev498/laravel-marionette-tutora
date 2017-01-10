<?php

namespace App\Handlers\Commands;

use App\Student;
use App\User;
use App\Tutor;
use App\Job;
use App\Commands\UserRelationshipsCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Auth\Exceptions\UnauthorizedException;
use Illuminate\Support\Collection;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Search\Exceptions\NotFoundException;
use App\Repositories\Contracts\RelationshipRepositoryInterface;

class UserRelationshipsCommandHandler extends CommandHandler
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
     * RelationshipRepositoryInterface
     */
    protected $relationships;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                         $database
     * @param  Auth                             $auth
     * @param  UserRepositoryInterface          $users
     * @param  RelationshipRepositoryInterface  $relationships
     */
    public function __construct(
        Database                        $database,
        Auth                            $auth,
        UserRepositoryInterface         $users,
        RelationshipRepositoryInterface $relationships
    ) {
        $this->database         = $database;
        $this->auth             = $auth;
        $this->users            = $users;
        $this->relationships    = $relationships;
    }

    /**
     * @param UserRelationshipsCommand $command
     *
     * @return Collection
     *
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function handle(UserRelationshipsCommand $command)
    {
        $user = $this->users->findByUuid($command->uuid);
        if(!$user) {
            throw new NotFoundException();
        }

        // Guard
        $this->guardAgainstUnauthorized($user);

        $relationships = $this->getRelationships($user);

        // Return
        return [$user, $relationships];
    }

    /**
     * @param User $user
     *
     * @return Collection
     */
    protected function getRelationships(User $user)
    {
        $items = $this->relationships
            ->with([
                'message',
                'message.lines:last',
                'tasks:next',
            ])
            ->getByUser($user);

        return $items;
    }

    /**
     * Guard against a user not having permission
     *
     * @param  User $user
     * @return void
     *
     * @throws UnauthorizedException
     */
    protected function guardAgainstUnauthorized(User $user)
    {
        $authed = $this->auth->user();

        if ($authed->isAdmin() || $authed->id == $user->id) {
            return;
        }

        throw new UnauthorizedException();
    }
}

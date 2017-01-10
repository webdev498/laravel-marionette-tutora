<?php namespace App\Handlers\Commands;

use App\UserProfile;
use App\Commands\UserProfileUpdateCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Validators\UserProfileUpdateValidator;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Contracts\UserProfileRepositoryInterface;

class UserProfileUpdateCommandHandler extends CommandHandler
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
     * @var UpdateUserValidator
     */
    protected $validator;

    /**
     * @var UserProfileRepositoryInterface
     */
    protected $profiles;

    /**
     * Create an instance of the handler.
     *
     * @param  Database                   $databaes
     * @param  Auth                       $auth
     * @param  UserProfileUpdateValidator $validator
     * @return void
     */
    public function __construct(
        Database                       $database,
        Auth                           $auth,
        UserProfileUpdateValidator     $validator,
        UserProfileRepositoryInterface $profiles
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->validator = $validator;
        $this->profiles  = $profiles;
    }

    /**
     * Execute the command.
     *
     * @param  UserProfileUpdateCommand $command
     * @return UserProfile
     */
    public function handle(UserProfileUpdateCommand $command)
    {
        return $this->database->transaction(function() use ($command) {
            $this->guardAgainstInvalidData($command);

            $user    = $this->auth->user();
            $profile = UserProfile::amend($user->profile, (array) $command);

            $this->profiles->save($profile);

            $this->dispatchFor($profile);

            return $profile;
        });
    }

    /**
     * Guard against invalid data on the command.
     *
     * @throws ValidationException
     * @return void
     */
    protected function guardAgainstInvalidData($command)
    {
        $this->validator->validate((array) $command);
    }

}

<?php namespace App\Handlers\Commands;


use App\Events\UserReviewWasLeft;
use App\Tutor;
use App\Student;
use App\UserReview;
use App\Validators\UpdateUserReviewValidator;
use Illuminate\Database\DatabaseManager;
use App\Commands\UpdateUserReviewCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Validators\CreateUserReviewValidator;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\UserReviewRepositoryInterface;
use App\Auth\Exceptions\UnauthorizedException;

class UpdateUserReviewCommandHandler extends CommandHandler
{

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var UpdateUserReviewValidator
     */
    protected $validator;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var UserReviewRepositoryInterface
     */
    protected $reviews;

    /**
     * Create the command handler.
     *
     * @param  DatabaseManager               $database
     * @param  Auth                          $auth
     * @param  CreateUserReviewValidator     $validator
     * @param  UserRepositoryInterface       $users
     * @param  UserReviewRepositoryInterface $reviews
     */
    public function __construct(
        DatabaseManager               $database,
        Auth                          $auth,
        UpdateUserReviewValidator     $validator,
        UserRepositoryInterface       $users,
        UserReviewRepositoryInterface $reviews
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->validator = $validator;
        $this->users     = $users;
        $this->reviews   = $reviews;
    }

    /**
     * Handle the command.
     *
     * @param  UpdateUserReviewCommand  $command
     *
     * @return UserReview
     */
    public function handle(UpdateUserReviewCommand $command)
    {
        return $this->database->transaction(function () use ($command) {

            // Validate
            $this->guardAgainstInvalidData($command);

            $ur = UserReview::find($command->id);

            $ur->body = $command->body;
            $ur->rating = $command->rating;
            $ur->save();

            event(new UserReviewWasLeft($ur));

            // Dispatch
            $this->dispatchFor($ur);

            // Return
            return $ur;
        });
    }

    /**
     * Guard against invalid data on the command.
     *
     * @param  CreateUserReviewCommand $command
     * @return void
     */
    protected function guardAgainstInvalidData(
        UpdateUserReviewCommand $command
    ) {
        return $this->validator->validate((array) $command);
    }

    protected function guardAgainstUnauthorisedUser(
        Student $student,
        Tutor   $tutor
    ) {
        $current = $this->auth->user();

        if($current->id != $student->id && !$current->isAdmin()) {
            throw new UnauthorizedException();
        }
    }

}

<?php namespace App\Handlers\Commands;

use App\User;
use App\Tutor;
use App\Student;
use App\UserReview;
use App\LessonBooking;
use Illuminate\Database\DatabaseManager;
use App\Commands\CreateUserReviewCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Validators\CreateUserReviewValidator;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\UserReviewRepositoryInterface;
use App\Auth\Exceptions\UnauthorizedException;

class CreateUserReviewCommandHandler extends CommandHandler
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
     * @var CreateUserReviewValidator
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
        CreateUserReviewValidator     $validator,
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
     * @param  CreateUserReviewCommand  $command
     *
     * @return UserReview
     */
    public function handle(CreateUserReviewCommand $command)
    {
        return $this->database->transaction(function () use ($command) {

            // Validate
            $this->guardAgainstInvalidData($command);

            // Lookups
            if($command->studentUuid) {
                $student = $this->users->findByUuid($command->studentUuid);
            } else {
                $student = $this->auth->user();
            }
            $tutor = $this->users->findByUuid($command->uuid);

            // Guard
            $this->guardAgainstUnauthorisedUser($student, $tutor);

            // Create
            $review = UserReview::leave(
                (string) $command->rating,
                (string) $command->body,
                $student
            );

            // Save
            $this->reviews->saveForTutor($review, $tutor);

            // Dispatch
            $this->dispatchFor($review);

            // Return
            return $review;
        });
    }

    /**
     * Guard against invalid data on the command.
     *
     * @param  CreateUserReviewCommand $command
     * @return void
     */
    protected function guardAgainstInvalidData(
        CreateUserReviewCommand $command
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

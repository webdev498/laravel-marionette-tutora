<?php namespace App\Handlers\Commands;

use App\Auth\Exceptions\UnauthorizedException;
use App\Events\UserReviewWasLeft;
use App\Repositories\Contracts\UserReviewRepositoryInterface;
use App\User;
use App\UserReview;
use Illuminate\Database\DatabaseManager;
use App\Commands\DeleteUserReviewCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use Mockery\CountValidator\Exception;

class DeleteUserReviewCommandHandler extends CommandHandler
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
     * @var UserReviewRepositoryInterface
     */
    protected $reviews;

    /**
     * @param DatabaseManager $database
     * @param Auth $auth
     * @param UserReviewRepositoryInterface $reviews
     */
    public function __construct(
        DatabaseManager               $database,
        Auth                          $auth,
        UserReviewRepositoryInterface $reviews
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->reviews   = $reviews;
    }

    /**
     * Handle the command.
     *
     * @param  DeleteUserReviewCommand  $command
     * @return $tutor
     */
    public function handle(DeleteUserReviewCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            $this->guardAgainstUserNotPermitted();

            $review = $this->findUserReview($command->id);

            // Set user review to deleted
            $review->delete();

            $this->updateProfile($review);

            return $review;
        });
    }

    /**
     * @param $id
     * @return mixed
     * @throws TutorNotFoundException
     */
    protected function findUserReview($id)
    {
        $review = $this->reviews->findById($id);

        if ( ! $review) {
            throw new Exception();
        }

        return $review;
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

    /**
     * @param Tutor $tutor
     * @return bool
     */
    protected function updateProfile(UserReview $review)
    {
        event(new UserReviewWasLeft($review));
    }

}

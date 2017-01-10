<?php namespace App\Handlers\Commands;

use App\Tutor;
use App\User;
use App\Validators\QuizValidator;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Commands\QuizSubmitCommand;
use App\Auth\Exceptions\UnauthorizedException;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Contracts\UserProfileRepositoryInterface;
use App\Events\QuizWasSubmitted;

class QuizSubmitCommandHandler extends CommandHandler
{

    /**
     * The Guard implementation.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * @var QuizValidator
     */
    protected $validator;

    /**
     * @var UserProfileRepositoryInterface
     */
    protected $profiles;

    /**
     * Create an instance of the handler.
     *
     * @param  Auth auth
     * @param  QuizValidator $validator
     * @param  UserProfileRepositoryInterface $profiles
     * @return void
     */
    public function __construct(
        Auth                       $auth,
        QuizValidator          $validator,
        UserProfileRepositoryInterface $profiles
    ) {
        $this->auth      = $auth;
        $this->validator = $validator;
        $this->profiles     = $profiles;
    }

    /**
     * Execute the command.
     *
     * @param  QuizSubmitCommand $command
     * @return boolean
     */
    public function handle(QuizSubmitCommand $command)
    {
        // Validate
        $this->guardAgainstInvalidData($command);

        $user    = $this->auth->user();
        $profile = $user->profile;

        // Event
        $this->dispatch(new QuizWasSubmitted($profile));

        return $command->answers;
    }

    /**
     * Guard against invalid data on the command
     *
     * @throws ValidationException
     * @param  QuizSubmitCommand $command
     * @return void
     */
    public function guardAgainstInvalidData(QuizSubmitCommand $command)
    {
        return $this->validator->validate((array) $command);
    }

}

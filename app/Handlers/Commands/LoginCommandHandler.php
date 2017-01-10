<?php namespace App\Handlers\Commands;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Auth\Exceptions\IncorrectCredentialsException;
use App\Auth\Exceptions\IncorrectPasswordException;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Validators\LoginValidator;
use App\Commands\LoginCommand;

class LoginCommandHandler extends CommandHandler
{
    /**
     * @var LoginValidator
     */
    protected $validator;

    /**
     * @var Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /*
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @param Illuminate\Contracts\Auth\Guard $auth
     * @param App\Validators\LoginValidator   $validator
     */
    public function __construct(
        LoginValidator          $validator,
        Auth                    $auth,
        UserRepositoryInterface $users
    ) {
        $this->validator = $validator;
        $this->auth      = $auth;
        $this->users     = $users;
    }

    /**
     * Execute the command.
     *
     * @param LoginCommand $command
     * @return User
     */
    public function handle(LoginCommand $command)
    {
        if ($this->auth->guest()) {
            $this->guardAgainstInvalidData($command);

            if ( ! $this->auth->attempt([
                'email'    => $command->email,
                'password' => $command->password,
                'deleted_at' => null,
                'blocked_at' => null
            ], $command->remember_me)) {
                if ($this->users->countByEmail($command->email) > 0) {
                    throw new IncorrectPasswordException();
                }

                throw new IncorrectCredentialsException();
            }
        }

        return $this->auth->user();
    }

    /**
     * Guard against invalid data on the comand
     *
     * @throws ValidationException
     * @return void
     */
    protected function guardAgainstInvalidData($command)
    {
        $this->validator->validate([
            'email'       => $command->email,
            'password'    => $command->password,
            'remember_me' => $command->remember_me,
        ]);
    }

}

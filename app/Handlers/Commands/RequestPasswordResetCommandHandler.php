<?php namespace App\Handlers\Commands;

use App\Mailers\PasswordMailer;
use App\Commands\RequestPasswordResetCommand;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;

class RequestPasswordResetCommandHandler extends CommandHandler
{

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var TokenRepositoryInterface
     */
    protected $tokens;

    /**
     * @var PasswordMailer
     */
    protected $mailer;

    /**
     * Create the command handler.
     *
     * @param  UserRepositoryInterface  $users
     * @param  TokenRepositoryInterface $tokens
     * @param  PasswordMailer           $mailer
     * @return void
     */
    public function __construct(
        UserRepositoryInterface  $users,
        TokenRepositoryInterface $tokens,
        PasswordMailer           $mailer
    ) {
        $this->users  = $users;
        $this->tokens = $tokens;
        $this->mailer = $mailer;
    }

    /**
     * Handle the command.
     *
     * @param  RequestPasswordResetCommand  $command
     * @return void
     */
    public function handle(RequestPasswordResetCommand $command)
    {
        $user = $this->users->findByEmail($command->email);

        if ($user !== null) {
            $token = $this->tokens->create($user);

            $this->mailer->sendRequestPasswordResetMessageTo($user, $token);
        }

        return $command->email;
    }

}

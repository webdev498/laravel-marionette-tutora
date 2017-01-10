<?php namespace App\Handlers\Commands;

use App\User;
use App\Commands\ResetPasswordCommand;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;

class ResetPasswordCommandHandler extends CommandHandler
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
     * Create the command handler.
     *
     * @param  UserRepositoryInterface  $users
     * @param  TokenRepositoryInterface $tokens
     * @return void
     */
    public function __construct(
        UserRepositoryInterface  $users,
        TokenRepositoryInterface $tokens
    ) {
        $this->users  = $users;
        $this->tokens = $tokens;
    }

    /**
     * Handle the command.
     *
     * @param  ResetPasswordCommand  $command
     * @return void
     */
    public function handle(ResetPasswordCommand $command)
    {
        $user = $this->users->findByEmail($command->email);

        if ($user !== null && $this->tokens->exists($user, $command->token)) {
            $user = User::changePassword($user, $command->password);

            $this->users->save($user);

            $this->tokens->delete($command->token);
        }
    }

}

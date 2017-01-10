<?php namespace App\Handlers\Commands\Messages;

use App\User;
use App\MessageLine;
use App\Commands\Messages\FlagMessageLineCommand;
use App\Handlers\Commands\CommandHandler;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Contracts\Validation\UnauthorizedException;
use App\Repositories\Contracts\MessageLineRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class FlagMessageLineCommandHandler extends CommandHandler
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
     * @var MessageLineRepositoryInterface
     */
    protected $messageLines;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var Auth
     */
    protected $auth;
    
    /**
     * Create a new handler instance.
     *
     * @param  Database                         $database
     * @param  UserRepositoryInterface          $users
     * @param  MessageLineRepositoryInterface   $lines
     * @param  Auth                             $auth
     */
    public function __construct(
        Database                        $database,
        UserRepositoryInterface         $users,
        MessageLineRepositoryInterface  $lines,
        Auth                            $auth
    ) {
        $this->database         = $database;
        $this->users            = $users;
        $this->lines            = $lines;
        $this->auth             = $auth;
    }

    /**
     * Execute the command.
     *
     * @param  FlagMessageLineCommand $command
     *
     * @return MessageLine
     */
    public function handle(FlagMessageLineCommand $command)
    {
        return $this->database->transaction(function () use ($command) {

            $line  = $this->lines->findById($command->uuid);

            $this->guardAgainstUnauthorized($line);

            $line = $this->flagLine($line);

            $this->dispatchFor($this->dispatch);

            return $line;
        });
    }

    /**
     * @param MessageLine $line
     *
     * @return MessageLine
     */
    protected function flagLine(MessageLine $line)
    {
        $previous = $line->flagged;

        $line->flagged = !$previous;
        $line->save();

        // Dispatch
        $this->dispatch[] = $line;

        return $line;
    }

    /**
     * Guard against unauthorised request
     *
     * @throws UnauthorizedException
     * @param  MessageLine $line
     *
     * @return void
     */
    protected function guardAgainstUnauthorized(MessageLine $line)
    {
        $auth = $this->auth->user();

        $isAdmin = $auth->isAdmin();

        if (!$isAdmin) {
            throw new UnauthorizedException();
        }
    }

}

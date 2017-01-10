<?php namespace App\Handlers\Commands\Jobs;

use App\Admin;
use App\Tutor;
use App\User;
use App\Location;
use App\Job;
use App\Commands\Jobs\DeleteJobCommand;
use App\Handlers\Commands\CommandHandler;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Contracts\Validation\UnauthorizedException;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Events\Jobs\JobWasRemoved;

class DeleteJobCommandHandler extends CommandHandler
{
    /**
     * @var Database
     */
    protected $database;

    /**
     * @var JobRepositoryInterface
     */
    protected $jobs;

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
     * @param  Database                    $database
     * @param  JobRepositoryInterface      $jobs
     * @param  Auth                        $auth
     */
    public function __construct(
        Database                    $database,
        JobRepositoryInterface      $jobs,
        Auth                        $auth
    ) {
        $this->database         = $database;
        $this->jobs             = $jobs;
        $this->auth             = $auth;
    }

    /**
     * Execute the command.
     *
     * @param  DeleteJobCommand $command
     */
    public function handle(DeleteJobCommand $command)
    {
        return $this->database->transaction(function () use ($command) {

            $job   = $this->jobs->findByUuid($command->uuid);
            $owner = $job->user;

            $this->guardAgainstUnauthorized($owner);

            $this->deleteJob($job, $command);

            $this->dispatch(new JobWasRemoved($owner, $command->uuid));
        });
    }

    /**
     * @param Job              $job
     * @param DeleteJobCommand $command
     *
     * @return boolean
     */
    protected function deleteJob(Job $job, DeleteJobCommand $command)
    {
        return $this->jobs->delete($job);
    }

    /**
     * Guard against unauthorised request
     *
     * @throws UnauthorizedException
     * @param  User $owner
     *
     * @return void
     */
    protected function guardAgainstUnauthorized(User $owner)
    {
        $authed = $this->auth->user();

        $isAdmin = $authed->isAdmin();

        if (!$isAdmin) {
            throw new UnauthorizedException();
        }
    }

}

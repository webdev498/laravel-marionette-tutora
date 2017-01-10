<?php namespace App\Handlers\Commands\Jobs;

use App\Tutor;
use App\User;
use App\Job;
use App\Search;
use App\Search\Results;
use App\Commands\Jobs\FavouriteJobCommand;
use App\Handlers\Commands\CommandHandler;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Contracts\Validation\UnauthorizedException;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class FavouriteJobCommandHandler extends CommandHandler
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
     * @param  UserRepositoryInterface     $users
     * @param  JobRepositoryInterface      $jobs
     * @param  Auth                        $auth
     */
    public function __construct(
        Database                    $database,
        UserRepositoryInterface     $users,
        JobRepositoryInterface      $jobs,
        Auth                        $auth
    ) {
        $this->database         = $database;
        $this->users            = $users;
        $this->jobs             = $jobs;
        $this->auth             = $auth;
    }

    /**
     * Execute the command.
     *
     * @param  FavouriteJobCommand $command
     *
     * @return Results
     */
    public function handle(FavouriteJobCommand $command)
    {
        return $this->database->transaction(function () use ($command) {

            $job  = $this->jobs->findByUuid($command->uuid);
            $user = $command->user;

            $this->guardAgainstUnauthorized($user);

            $job = $this->favouriteJob($job, $user);

            $this->dispatchFor($this->dispatch);

            return $job;
        });
    }

    /**
     * @param Job   $job
     * @param Tutor $tutor
     *
     * @return Job
     */
    protected function favouriteJob(Job $job, Tutor $tutor)
    {
        $tutorJob = $this->getTutorJob($job, $tutor);

        $alreadyFavourited = $tutorJob->pivot->favourite;

        $tutorJob->pivot->favourite = !$alreadyFavourited;
        $tutorJob->pivot->save();

        // Dispatch
        $this->dispatch[] = $tutorJob;

        return $tutorJob;
    }

    /**
     * @param Job   $job
     * @param Tutor $tutor
     *
     * @return Job
     */
    protected function getTutorJob(Job $job, Tutor $tutor)
    {
        $tutorJob = $tutor->jobs()->find($job->id);

        if(!$tutorJob) {
            $tutor->jobs()->attach($job);
            $tutorJob = $tutor->jobs()->find($job->id);
        }

        return $tutorJob;
    }

    /**
     * Guard against unauthorised request
     *
     * @throws UnauthorizedException
     * @param  User $user
     *
     * @return void
     */
    protected function guardAgainstUnauthorized(User $user)
    {
        $auth = $this->auth->user();

        $isTutor = $user->isTutor();
        $isUserHimself = $user->id == $auth->id;

        if (!$isTutor || !$isUserHimself) {
            throw new UnauthorizedException();
        }
    }

}

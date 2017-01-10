<?php namespace App\Handlers\Commands\Jobs;

use App\Tutor;
use App\User;
use App\Relationship;
use App\Message;
use App\MessageLine;
use App\Job;
use App\Validators\JobApplicationValidator;
use App\Commands\Jobs\SendJobApplicationCommand;
use App\Handlers\Commands\CommandHandler;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Contracts\Validation\UnauthorizedException;
use App\Database\Exceptions\DuplicateResourceException;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Exceptions\Jobs\ClosedJobException;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\MessageLineRepositoryInterface;

use App\Events\Jobs\JobWasApplied;

class SendJobApplicationCommandHandler extends CommandHandler
{
    /**
     * @var array
     */
    protected $dispatch = [];

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var JobApplicationValidator
     */
    protected $validator;

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
     * @var MessageRepositoryInterface
     */
    protected $messages;

    /**
     * @var MessageLineRepositoryInterface
     */
    protected $lines;

    /**
     * @var Job
     */
    protected $tutorJob = null;
    
    /**
     * Create a new handler instance.
     *
     * @param  Database                       $database
     * @param  JobApplicationValidator        $validator
     * @param  UserRepositoryInterface        $users
     * @param  JobRepositoryInterface         $jobs
     * @param  Auth                           $auth
     * @param  MessageRepositoryInterface     $messages
     * @param  MessageLineRepositoryInterface $lines
     */
    public function __construct(
        Database                       $database,
        JobApplicationValidator        $validator,
        UserRepositoryInterface        $users,
        JobRepositoryInterface         $jobs,
        Auth                           $auth,
        MessageRepositoryInterface     $messages,
        MessageLineRepositoryInterface $lines
    ) {
        $this->database         = $database;
        $this->validator        = $validator;
        $this->users            = $users;
        $this->jobs             = $jobs;
        $this->auth             = $auth;
        $this->messages         = $messages;
        $this->lines            = $lines;
    }

    /**
     * Execute the command.
     *
     * @param  SendJobApplicationCommand $command
     *
     * @return array
     */
    public function handle(SendJobApplicationCommand $command)
    {
        return $this->database->transaction(function () use ($command) {

            $this->guardAgainstInvalidData($command);

            $job = $command->job;

            $this->guardAgainstClosedJob($job);

            $tutor          = $this->getTutor($command);
            $relationship   = $command->relationship;

            $this->dispatch[] = $job;
            $this->dispatch[] = $tutor;

            $this->guardAgainstUnauthorized($tutor);
            $this->guardAgainstAlreadyAppliedJob($job, $tutor);

            $this->updateRelationship($relationship);

            list($message, $line) = $this->createMessage($tutor, $command);

            $this->connectJobToMessageLine($job, $line);
            $this->connectJobToTutor($job, $tutor);

            $job->raise(new JobWasApplied($job));

            $this->dispatchFor($this->dispatch);

            return [$message, $line];
        });
    }

    /**
     * @param Relationship $relationship
     */
    protected function updateRelationship(Relationship $relationship)
    {
        $relationship->status = Relationship::REQUESTED_BY_TUTOR;
        $relationship->is_application = true;
        $relationship->save();

    }

    /**
     * @param SendJobApplicationCommand $command
     *
     * @return Tutor
     */
    protected function getTutor(SendJobApplicationCommand $command)
    {
        $tutor = $command->relationship->tutor;

        return $tutor;
    }

    /**
     * @param Job $job
     * @param MessageLine $line
     */
    protected function connectJobToMessageLine(Job $job, MessageLine $line)
    {
        $job->messageLines()->attach($line);
    }

    /**
     * @param Job $job
     * @param Tutor $tutor
     */
    protected function connectJobToTutor(Job $job, Tutor $tutor)
    {
        $tutorJob = $this->getTutorJob($job, $tutor);

        $tutorJob->pivot->applied = true;

        $tutorJob->pivot->save();
    }

    /**
     * @param Job   $job
     * @param Tutor $tutor
     *
     * @return Job
     */
    protected function getTutorJob(Job $job, Tutor $tutor)
    {
        if($this->tutorJob) {return $this->tutorJob;}

        $tutorJob = $tutor->jobs()->find($job->id);

        if(!$tutorJob) {
            $tutor->jobs()->attach($job);
            $tutorJob = $tutor->jobs()->find($job->id);
        }

        $this->tutorJob = $tutorJob;

        return $tutorJob;
    }

    /**
     * @param Tutor                     $tutor
     * @param SendJobApplicationCommand $command
     *
     * @return Message
     */
    protected function createMessage(Tutor $tutor, SendJobApplicationCommand $command)
    {
        // Data
        $body         = (string) $command->body;
        $relationship = $command->relationship;

        $message = $this->getMessage($relationship);

        // Line
        $line = MessageLine::writeApplication($body, $tutor);

        // Save
        if (!$message->exists) {
            $relationship->message()->save($message);
        }

        $message->lines()->save($line);
        $message->touch();

        $this->dispatch[] = $line;

        return [$message, $line];
    }

    /**
     * Open a new message
     *
     * @param  Relationship $relationship
     *
     * @return Message
     *
     * @throws ResourceNotFoundException
     */
    protected function getMessage(Relationship $relationship)
    {
        return $relationship->message ?: Message::open(Message::generateUuid());
    }

    /**
     * Guard against invalid data on the command
     *
     * @throws ValidationException
     * @return void
     */
    protected function guardAgainstInvalidData($command)
    {
        $this->validator->validate((array) $command);
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
        $authed = $this->auth->user();

        $isAdmin     = $authed->isAdmin();
        $isSelfEdit  = $authed->id === $user->id;

        if (!$isSelfEdit && !$isAdmin) {
            throw new UnauthorizedException();
        }
    }

    /**
     * Guard against already applied job request
     *
     * @throws DuplicateResourceException
     *
     * @param  Job   $job
     * @param  Tutor $tutor
     *
     * @return void
     */
    protected function guardAgainstAlreadyAppliedJob(Job $job, Tutor $tutor)
    {
        $tutorJob = $this->getTutorJob($job, $tutor);

        $isApplied = $tutorJob->pivot->applied == true;

        if($isApplied) {
            throw new DuplicateResourceException();
        }
    }

    /**
     * Guard against closed job
     *
     * @throws ClosedJobException
     *
     * @param  Job   $job
     *
     * @return void
     */
    protected function guardAgainstClosedJob(Job $job)
    {
        $isClosed   = $job->status == Job::STATUS_CLOSED;
        $isExpired  = $job->status == Job::STATUS_EXPIRED;

        if($isClosed || $isExpired) {
            throw new ClosedJobException();
        }
    }

    /**
     * Check if auth admin
     *
     * @return boolean
     */
    protected function isAdmin()
    {
        $authed = $this->auth->user();

        return $authed->isAdmin();
    }

}

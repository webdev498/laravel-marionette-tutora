<?php

namespace App\Handlers\Commands;

use App\Student;
use App\User;
use App\Tutor;
use App\Message;
use App\MessageLine;
use App\Relationship;
use App\Commands\SendMessageCommand;
use App\Validators\SendMessageValidator;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as Database;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\MessageLineRepositoryInterface;

class SendMessageCommandHandler extends CommandHandler
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
     * @var SendMessageValidator
     */
    protected $validator;

    /**
     * @var MessageRepositoryInterface
     */
    protected $messages;

    /**
     * @var MessageLineRepositoryInterface
     */
    protected $lines;

    /**
     * Create the command handler.
     *
     * @param  Database                       $database
     * @param  Auth                           $auth
     * @param  SendMessageValidator           $validator
     * @param  MessageRepositoryInterface     $messages
     * @param  MessageLineRepositoryInterface $lines
     */
    public function __construct(
        Database                       $database,
        Auth                           $auth,
        SendMessageValidator           $validator,
        MessageRepositoryInterface     $messages,
        MessageLineRepositoryInterface $lines
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->validator = $validator;
        $this->messages  = $messages;
        $this->lines     = $lines;
    }

    /**
     * Handle the command.
     *
     * @param  SendMessageCommand $command
     * @return void
     */
    public function handle(SendMessageCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            // Validate
            $this->guardAgainstInvalidData($command);
            // Lookups
            $user    = $this->auth->user();
            $message = $this->findMessage($command->uuid, $command->relationship);
            $relationship = $message->relationship;

            // Data
            $body       = $this->getBody($command);
            $fromSystem = $command->from_system === true;
            $silent     = $command->silent      === true;

            // Guard
            if ( ! $fromSystem) {
                $this->guardAgainstUnauthorized($message, $user);
            }
            // Line
            $line = MessageLine::write($body, $fromSystem ? null : $user);

            // Relationship

            if ($user instanceof Tutor && ! $fromSystem && ! $silent)
            $relationship->setTutorIntentToHelp(
                $command->intent,
                $command->reason
            );

            $this->updateRelationshipStatus($message, $user);

            // Save
            if ( ! $message->exists) {
                $relationship->message()->save($message);
            }
            $message->lines()->save($line);
            $message->touch();

            // Dispatch
            if ( ! $silent) {
                $this->dispatchFor($line);
            }

            return $message;
        });
    }

    protected function getBody(SendMessageCommand $command)
    {
        $body = $command->body;

        if($command->location_postcode) {
            $body .= "\n" . 'Postcode: ' . $command->location_postcode;
        }

        if($command->subject_name) {
            $body .= "\n" . 'Subject: ' . $command->subject_name;
        }

        return $body;
    }

    /**
     * Find the message from a given relationship, or, open a new one
     *
     * @param  string       $uuid
     * @param  Relationship $relationship
     * @return Message
     */
    protected function findMessage($uuid, Relationship $relationship = null)
    {
        // Relationship?
        if ($relationship) {
            return $relationship->message ?: Message::open($this->generateUuid());
        }
        // By uuid
        $message = $this->messages->findByUuid($uuid);
        // Throw
        if ( ! $message) {
            throw new ResourceNotFoundException();
        }
        // Return
        return $message;
    }

    /**
     * @param Message $message
     * @param User $user
     */
    protected function updateRelationshipStatus(Message $message, User $user)
    {
        $relationship = $message->relationship;
        $isStudent    = $user instanceof Student;

        if($relationship->status == Relationship::REQUESTED_BY_TUTOR && $isStudent) {
            $relationship->status = Relationship::CHATTING;
            $relationship->save();
        }
    }

    /**
     * Generate a uuid, ensuring it is, in fact, unique to the message
     *
     * @return string
     */
    protected function generateUuid()
    {
        do {
            $uuid = str_uuid();
        } while ($this->messages->countByUuid($uuid) > 0);
        return $uuid;
    }

    /**
     * Guard against invalid data on the command
     *
     * @throws ValidationException
     * @param  SendMessageCommand $command
     * @return void
     */
    protected function guardAgainstInvalidData($command)
    {
        $this->validator->validate((array) $command);
    }

    /**
     * Guard against unauthorised message sending
     *
     * @throws UnauthorizedException
     * @param  Message $message
     * @param  User    $user
     * @return void
     */
    protected function guardAgainstUnauthorized(Message $message, User $user)
    {
        $tutor   = $message->relationship->tutor;
        $student = $message->relationship->student;

        if ($tutor->id !== $user->id && $student->id !== $user->id) {
            throw new UnauthorizedException();
        }
    }

}

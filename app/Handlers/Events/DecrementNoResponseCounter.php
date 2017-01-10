<?php namespace App\Handlers\Events;

use App\User;
use App\Tutor;
use App\UserProfile;
use App\Events\MessageLineWasWritten;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\UserProfileRepositoryInterface;

class DecrementNoResponseCounter extends EventHandler
{

    /**
     * @var UserProfile $profiles
     */
    protected $profiles;

    /**
     * Create the event handler.
     *
     * @param  UserProfileRepositoryInterface $profiles
     * @param  MessageRepositoryInterface $messages
     * @return void
     */
    public function __construct(
        UserProfileRepositoryInterface $profiles,
        MessageRepositoryInterface $messages
    ) {
        $this->profiles   = $profiles;
        $this->messages   = $messages;
    }

    /**
     * Handle the event.
     *
     * @param  MessageLineWasWritten $event
     * @return void
     */
    public function handle(MessageLineWasWritten $event)
    {
        $line = $event->line;
        $sender = $line->user;

        $message = $line->message;
        $relationship = $message->relationship;
        $tutor = $relationship->tutor;
        $profile = $tutor->profile;

        // Decrement if message lines written by tutor are exactly 1 and the tutor sent the message
        $count = $this->messages->countMessageLinesByMessageAndUser($message->id, $tutor);

        if ($count == 1 && $sender instanceof Tutor) {
            $profile = $this->decrementNoResponseCounter($profile);
            $this->profiles->save($profile);
        }


    }

    protected function decrementNoResponseCounter($profile) 
    {
        
        if ($profile->no_response_counter > 0) {
            $profile->no_response_counter = $profile->no_response_counter - 1;
        }

        return $profile;
    }

}

<?php

namespace App\Handlers\Events;

use App\Message;
use App\Events\RelationshipWasMade;
use App\Repositories\Contracts\MessageRepositoryInterface;

class CreateMessage extends EventHandler
{
    /**
     * @var MessageRepositoryInterface
     */
    protected $messages;

    /**
     * Create the command handler.
     *
     * @param  MessageRepositoryInterface $messages
     * @return void
     */
    public function __construct(
        MessageRepositoryInterface $messages
    ) {
        $this->messages = $messages;
    }

    /**
     * Handle the event.
     *
     * @param  RelationshipWasMade $event
     * @return void
     */
    public function handle(RelationshipWasMade $event)
    {
        // Lookup
        $relationship = $event->relationship;
        // Make
        if ( ! $relationship->message) {
            $uuid    = $this->generateUuid();
            $message = Message::open($uuid);
            // Save
            $relationship->message()->save($message);
            // Reload
            $relationship->load('message');
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
}

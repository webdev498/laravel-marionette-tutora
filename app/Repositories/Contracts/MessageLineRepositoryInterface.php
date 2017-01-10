<?php namespace App\Repositories\Contracts;

use App\Message;
use App\MessageLine;

interface MessageLineRepositoryInterface
{

    /**
     * Create an instance of the repository.
     *
     * @param  MessageLine $line
     * @return void
     */
    public function __construct(MessageLine $line);

    /**
     * Save a line for a message.
     *
     * @param  Message     $message
     * @param  MessageLine $line
     * @return MessageLine
     * @throws ResourceNotPersistedException
     */
    public function save(Message $message, MessageLine $line);

    /**
     * Find a messageLine by a given id
     *
     * @param Integer $id
     * @return MessageLine|null
     */
    public function findById($id);

}

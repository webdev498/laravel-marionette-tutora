<?php namespace App\Repositories\Contracts;

use App\User;
use App\Message;

interface MessageRepositoryInterface
{

    /**
     * @param Message     $message
     * @return Message|null
     */
    public function save(Message $message);

    /**
     * Find a message by a given id
     *
     * @param Integer $id
     * @return Message|null
     */
    public function findById($id);

    /**
     * Find a message by a given uuid
     *
     * @param String $uuid
     * @return Message|null
     */
    public function findByUuid($uuid);

    /**
     * Count the number of messages by a given uuid
     *
     * @param String $uuid
     * @return Message|null
     */
    public function countByUuid($uuid);

    /**
     * Get messages by a given user
     *
     * @param  User $user
     * @return Collection|null
     */
    public function getByUser(User $user, $page, $perPage);
}

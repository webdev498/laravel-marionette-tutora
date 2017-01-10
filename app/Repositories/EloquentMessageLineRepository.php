<?php namespace App\Repositories;

use App\User;
use App\Message;
use App\MessageLine;
use App\Database\Exceptions\ResourceNotPersistedException;
use App\Repositories\Contracts\MessageLineRepositoryInterface;

class EloquentMessageLineRepository extends AbstractEloquentRepository
    implements MessageLineRepositoryInterface
{

    /**
     * @var MessageLine
     */
    protected $line;

    /**
     * Create an instance of the repository.
     *
     * @param  MessageLine $line
     * @return void
     */
    public function __construct(MessageLine $line)
    {
        $this->line = $line;
    }

    /**
     * Get messagelines 
     *
     * @return Collection
     */
    public function get()
    {
        return $this->line
            ->newQuery()
            ->takePage($this->page, $this->perPage)
            ->orderBy('created_at', 'desc')
            ->with($this->with)
            ->get();
    }
 
    /**
     * Count flagged messagelines 
     *
     * @return Integer
     */
    public function count()
    {
        return $this->line
            ->newQuery()
            ->count();
    }

    /**
     * Get flagged messagelines 
     *
     * @return Collection
     */
    public function getByFlagged()
    {
        return $this->line
            ->newQuery()
            ->where('flagged', '=', 1)
            ->takePage($this->page, $this->perPage)
            ->orderBy('created_at', 'desc')
            ->with($this->with)
            ->get();
    }

    /**
     * Count flagged messagelines 
     *
     * @return Integer
     */
    public function countByFlagged()
    {
        return $this->line
            ->newQuery()
            ->where('flagged', '=', 1)
            ->count();
    }


    /**
     * Save a line for a message.
     *
     * @param  Message     $message
     * @param  MessageLine $line
     * @return MessageLine
     * @throws ResourceNotPersistedException
     */
    public function save(Message $message, MessageLine $line)
    {
        if ( ! $message->lines()->save($line)) {
            throw new ResourceNotPersistedException();
        }

        return $line;
    }

    /**
     * Find a messageLine by a given id
     *
     * @param Integer $id
     * @return MessageLine|null
     */
    public function findById($id) 
    {
        return $this->line
            ->whereId($id)
            ->first();
    }

    /**
     * Count number of lines in message from user. Used for reminders
     *
     * @param Message $message
     * @param User $user
     * @return integer
     */
    public function countByMessageAndUser(Message $message, User $user)
    {

        return $message->lines()
            ->where('user_id', $user->id)->count();  
    }

    public function recipients($line)
    {
        $user = $line->user;
        $message = $line->message;
        $relationship = $message->relationship;
        $recipients = [
            $relationship->tutor,
            $relationship->student,
        ];

        if ($user) {
            $recipients = array_filter($recipients, function ($user) use ($line) {
                return $user->id !== $line->user->id;
            });
            return $recipients;
        }

        return $recipients;


    }

}

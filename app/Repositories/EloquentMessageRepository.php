<?php namespace App\Repositories;

use DateTime;
use App\User;
use App\Message;
use App\MessageLine;
use App\Relationship;
use App\Tutor;
use Carbon\Carbon;
use App\Repositories\Traits\WithMessageLinesTrait;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Contracts\MessageRepositoryInterface;

class EloquentMessageRepository extends AbstractEloquentRepository
    implements MessageRepositoryInterface
{
    use WithMessageLinesTrait;

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Message
     */
    protected $message;

    /**
     * @param Database $database
     * @param Message  $message
     */
    public function __construct(
        Database $database,
        Message  $message
    ) {
        $this->database = $database;
        $this->message  = $message;
    }

    /**
     * Save a message.
     *
     * @param  Message $message
     * @return Message|null
     */
    public function save(Message $message)
    {
        if ($message->exists() === true) {
            $message->touch();
        }

        if ($message->save() !== true) {
            throw new ResourceNotPersistedException($message);
        }
    }

    /**
     * Find a message by a given id
     *
     * @param Integer $id
     * @return Message|null
     */
    public function findById($id)
    {
        return $this->message
            ->whereId($id)
            ->first();
    }

    /**
     * Find a message by a given uuid
     *
     * @param String $uuid
     * @return Message|null
     */
    public function findByUuid($uuid)
    {
        return $this->message
            ->where('uuid', '=', $uuid)
            ->with([
                'relationship',
                'relationship.tutor',
                'relationship.student',
                'lines' => function ($query) {
                    return $query
                        ->take(100)
                        ->orderBy('id', 'DESC'); // Faster than created_at
                }
            ])
            ->first();
    }

    /**
     * Count the number of messages by a given uuid
     *
     * @param String $uuid
     * @return Message|null
     */
    public function countByUuid($uuid)
    {
        return $this->message
            ->whereUuid($uuid)
            ->count();
    }

    public function countMessageLinesById($id)
    {
        return $this->message
            ->find($id)
            ->lines()
            ->count();
    }


    /**
     * Count MessageLines by MessageId and User
     *
     * @param  MessageId $id
     * @param  User $user
     * @return Integer
     */
    public function countMessageLinesByMessageAndUser($id, User $user)
    {
        return $this->message
            ->find($id)
            ->lines()
            ->where('user_id', '=', $user->id)
            ->count();
    }

    /**
     * Count MessageLines by MessageId and User after line
     *
     * @param  MessageId $id
     * @param  User $user
     * @return Integer
     */
    public function countMessageLinesByMessageAndUserAfterLine($id, User $user, $line)
    {
        return $this->message
            ->find($id)
            ->lines()
            ->where('user_id', '=', $user->id)
            ->where('created_at', '>', $line->created_at)
            ->count();
    }

    /**
     * Find a message by a given relationship
     *
     * @param  Relationship $relationship
     * @return Message
     */
    public function findByRelationship(Relationship $relationship)
    {
        return $relationship->message->load($this->with);
    }

    /**
     * Find a message by a given relationship, or fail
     *
     * @param  Relationship $relationship
     * @return Message
     */
    public function findByRelationshipOrFail(Relationship $relationship)
    {
        return $this->orFail($this->findByRelationship($relationship));
    }

    /**
     * Get messages
     *
     * @return Collection
     */
    public function get()
    {
        return $this->message
            ->newQuery()
            ->takePage($this->page, $this->perPage)
            ->orderBy('updated_at', 'desc')
            ->with($this->with)
            ->get();
    }

    /**
     * Get Messages with filter
     *
     * @return Collection
     */
    public function getByFlagged()
    {
        return $this->message
            ->newQuery()
            ->takePage($this->page, $this->perPage)
            ->orderBy('updated_at', 'desc')
            ->with($this->with)
            ->get();
    }


    /**
     * Count the messages
     *
     * @return integer
     */
    public function count()
    {
        return $this->message
            ->newQuery()
            ->count();
    }

    /**
     * Get messages by a given user.
     *
     * @param  User $user
     * @return Collection|null
     */
    public function getByUser(User $user, $page, $perPage)
    {
        $query = $user
            ->relationships()
            ->with([
                'message',
                'message.lines' => function ($query) {
                    // Grab the last line for each message
                    return $query
                        ->whereIn('id', function ($_query) {
                            return $_query
                                ->select($this->database->raw('MAX(`id`)'))
                                ->from('message_lines')
                                ->groupBy('message_id');
                        });
                    },
                'message.statuses' => function ($query) use ($user) {
                    // Grab just the status applying to the user
                    return $query->where('user_id', '=', $user->id);
                },
            ])
            ->takePage($page, $perPage)
            ->orderBy('updated_at', 'desc');

        if($user->isTutor()) {
            $query->where('relationships.status', '!=', Relationship::REQUESTED_BY_TUTOR);
        }

        return $query
            ->get()
            ->pluck('message')
            ->sortByDesc(function ($message) {
                return $message->updated_at;
            });
    }

    /**
     * Get all messages by a given user.
     *
     * @param  User $user
     * @return Collection|null
     */
    public function getAllByUser(User $user)
    {
        return $user
            ->relationships()
            ->with([
                'message',
                
            ])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->pluck('message')
            ->sortByDesc(function ($message) {
                return $message->updated_at;
            });
    }

    /**
     * Count the number of messages a given user has.
     *
     * @param  User $user
     * @return integer
     */
    public function countByUser(User $user)
    {
        return $user
            ->relationships()
            ->count();
    }


    /**
     * Count the number of messages a given user has. Hacky as not returninng correct values!
     *
     * @param  Datetime $date
     * @return collection
     */
    public function getByhasNoReply(DateTime $date)
    {

        $ids = \DB::table('messages')
            ->select('messages.id')
            ->join('relationships', 'messages.relationship_id', '=', 'relationships.id')
            ->where('relationships.status', '!=', [Relationship::NO_REPLY, Relationship::REQUESTED_BY_TUTOR])
            ->where('messages.created_at', '<', $date)
            ->whereIn('messages.id', function($q) {
                $q->select('messages.id')
                ->from('messages')
                ->join('message_lines', 'messages.id', '=', 'message_lines.message_id')
                ->groupBy('messages.id')
                ->havingRaw('COUNT(DISTINCT `message_lines`.`user_id`) + count(distinct case when `message_lines`.`user_id` is null then 1 end) < 2');
            })
            ->lists('messages.id');
        
        return $this->message->find($ids);

    }

    // Analytics Queries /////////////////////////////////////////////////////////////////////



}

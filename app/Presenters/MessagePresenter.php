<?php namespace App\Presenters;

use App\Message;
use App\MessageLine;
use App\MessageStatus;
use App\Messaging\Parser as MessageLineParser;
use App\Tutor;
use App\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class MessagePresenter extends AbstractPresenter
{

    /**
     * List of resources that are included by default.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'tutor',
        'student',
        'lines',
        'status',
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'relationship',

        'searches'
    ];

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(Message $message)
    {
        
        return [
            'uuid' => (string) $message->uuid,
            'time' => $this->formatHumanTime($message->updated_at),
            'hasReply' => (bool) $message->hasReply(),
        ];
    }

    /**
     * Include the tutor
     *
     * @return array
     */
    protected function includeTutor(Message $message)
    {
        $tutor = $message->relationship->tutor;

        return $this->item($tutor, new TutorPresenter());
    }

    /**
     * Include the student
     *
     * @return array
     */
    protected function includeStudent(Message $message)
    {
        $student = $message->relationship->student;

        return $this->item($student, new StudentPresenter());
    }

    /**
     * Include any lines
     *
     * @return array
     */
    protected function includeLines(Message $message)
    {
        $lines = $message->lines->sortBy('created_at');

        return $this->collection($lines, new MessageLinePresenter());
    }

    /**
     * Include any statuses
     *
     * @return array
     */
    protected function includeStatus(Message $message)
    {
       
        $status = $message->statuses->first();

        if (! $status) {
            $statuses = new MessageStatus;
        }

        return $this->item($status, new MessageStatusPresenter());
    }

    /**
     * Include the relationship
     *
     * @return array
     */
    protected function includeRelationship(Message $message)
    {
        return $this->item($message->relationship, new RelationshipPresenter());
    }
    
    /**
     * Include the searches
     *
     * @return array
     */
    protected function includeSearches(Message $message)
    {
        return $this->collection($message->relationship->searches, new SearchesPresenter());
    }
}


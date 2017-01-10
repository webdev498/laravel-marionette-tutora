<?php namespace App\Presenters;

use App\Message;
use App\MessageLine;
use App\MessageStatus;
use App\Messaging\Parser as MessageLineParser;
use App\Presenters\MessagePresenter;
use App\Tutor;
use App\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class MessageLinePresenter extends AbstractPresenter
{

    /**
     * List of resources that are included by default.
     *
     * @var array
     */
    protected $defaultIncludes = [
        
        
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'message',
        'relationship',
        'tutor',
        'student',
    ];


    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(MessageLine $line)
    {
        
        $parsed = MessageLineParser::make($line);

        return [
            'id'         => $line->id,
            'flag'       => $line->flagged,
            'who'        => $parsed->getWho(),
            'body'       => $parsed->getBody(),
            'short_body' => str_limit(strip_tags($parsed->getBody()), 40),
            'long_body' => strip_tags($parsed->getBody()),
            'time'       => $this->formatHumanTime($line->created_at),
        ];
    }

    /**
     * Include Flagged
     *
     * @return array
     */
    protected function includeFlagged(Messageline $line)
    {
        return $this->item($line->message, new MessagePresenter());
    }    

    /**
     * Include the message
     *
     * @return array
     */
    protected function includeMessage(Messageline $line)
    {
        return $this->item($line->message, new MessagePresenter());
    }

    /**
     * Include the relationship
     *
     * @return array
     */
    protected function includeRelationship(Messageline $line)
    {
        return $this->item($line->message->relationship, new RelationshipPresenter());
    }

    /**
     * Include the tutor
     *
     * @return array
     */
    protected function includeTutor(Messageline $line)
    {
        return $this->item($line->message->relationship->tutor, new TutorPresenter());
    }

    /**
     * Include the tutor
     *
     * @return array
     */
    protected function includeStudent(Messageline $line)
    {
        return $this->item($line->message->relationship->student, new StudentPresenter());
    }
}
<?php namespace App\Transformers;

use App\User;
use App\Tutor;
use App\Message;
use Carbon\Carbon;
use App\MessageLine;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use App\Messaging\Parser as MessageLineParser;

class MessageTransformer extends TransformerAbstract
{
    /**
     * List of resources that are included by default.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'tutor',
        'student',
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'lines',
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
            'time' => $this->formatTime($message->updated_at),
        ];
    }

    protected function formatTime($time)
    {
        return [
            'short' => $time->lt(Carbon::now()->addHour())
                ? $time->diffForHumans()
                : $time->format('D, H:i:s'),
            'long'  => $time->format('H:i:s, jS F Y'),
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

        return $this->item($tutor, new TutorTransformer());
    }

    /**
     * Include the student
     *
     * @return array
     */
    protected function includeStudent(Message $message)
    {
        $student = $message->relationship->student;

        return $this->item($student, new StudentTransformer());
    }

    protected function includeLines(Message $message)
    {
        return $this->collection($message->lines, function (MessageLine $line) {
            $parsed = MessageLineParser::make($line);
            return $parsed->toArray();
        });
    }

}

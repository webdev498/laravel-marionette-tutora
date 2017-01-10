<?php namespace App\Transformers;

use App\Message;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use App\Messaging\Parser as MessageLineParser;

class MessagesTransformer extends TransformerAbstract
{

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(Message $message)
    {
        return [
            'uuid'       => (string) $message->uuid,
            'recipients' => (string) $this->recipients($message),
            'last_line'  => (string) $this->lastLine($message),
            'when'       => (string) $this->when($message),
            'timestamp'  => (string) $this->timestamp($message),
        ];
    }

    protected function recipients(Message $message)
    {
        return $message->users
            ->map(function ($user) {
                return "{$user->first_name} {$user->last_name}";
            })
            ->implode(',');
    }

    protected function lastLine(Message $message)
    {
        $line = $message->lines->first();

        return MessageLineParser::make($line);
    }

    protected function when(Message $message)
    {
        $line = $message->lines->first();

        return $line->created_at->gt(Carbon::today())
            ? $line->created_at->format('H:i:s')
            : $line->created_at->format('M jS');
    }

    protected function timestamp(Message $message)
    {
        $line = $message->lines->first();

        return $line->created_at->format('H:i:s, jS F Y');
    }

}

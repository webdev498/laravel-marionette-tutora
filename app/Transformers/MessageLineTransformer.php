<?php namespace App\Transformers;

use App\MessageLine;
use League\Fractal\TransformerAbstract;

class MessageLineTransformer extends TransformerAbstract
{

    /**
     * Turn this object into a generic array
     *
     * @throws \Exception
     *
     * @param MessageLine $line
     *
     * @return array
     */
    public function transform(MessageLine $line)
    {
        $message = $line->message;
        $from    = $line->user;

        if ( ! $from) {
            throw new \Exception('No sender');
        }

        return [
            'id'            => $line->id,
            'flag'          => $line->flagged,
            'message_uuid'  => $message->uuid,
            'body'          => $line->body,
            'from_uuid'     => $from->uuid,
        ];
    }

}

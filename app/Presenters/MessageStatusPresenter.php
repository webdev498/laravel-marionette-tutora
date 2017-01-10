<?php

namespace App\Presenters;

use App\MessageStatus;
use App\Task;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class MessageStatusPresenter extends TransformerAbstract
{
    /**
     * Turn this object into a generic array
     *
     * @param  Task $task
     * @return array
     */
    public function transform(MessageStatus $status)
    {
        return [
            'id'         => (integer) $status->id,
            'unread'       => (bool)  $status->unread,
            'archived' => (bool)  $status->archived,
        ];
    }

}


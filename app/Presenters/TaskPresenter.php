<?php

namespace App\Presenters;

use App\Task;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TaskPresenter extends TransformerAbstract
{

    /**
     * Turn this object into a generic array
     *
     * @param  Task $task
     * @return array
     */
    public function transform(Task $task)
    {
        return [
            'id'         => (integer) $task->id,
            'category'   => (string) $task->category,
            'body'       => (string)  $task->body,
            'short_body' => (string)  str_limit($task->body, 40),
            'action_at'  => $this->formatTime($task->action_at),
        ];
    }

    protected function formatTime($time)
    {
        
        if($time == null) return;
        return [
            'value' => $time->format('Y-m-d'),
            'short' => $time->format('d/m/Y'),
            'long'  => $time->format('jS F Y'),
        ];
    }

}


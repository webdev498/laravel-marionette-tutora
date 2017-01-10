<?php

namespace App\Repositories\Traits;

use App\Task;

trait WithTasksTrait
{

    /**
     * Select only the next task due.
     *
     * @param  MorphMany $query
     * @param  string    $taskableType = taskable_type
     * @return MorphMany
     */
    protected function withTasksNext($query, $taskableType)
    {
        // $db = app('db');

        // // Build a subquery to select the ids
        // // for a given taskable_type that are
        // // ordered correctly
        // $ids = app(Task::class)
        //     ->newQuery()
        //     ->join('taskables', 'taskables.task_id', '=','tasks.id')
        //     ->where('taskables.taskable_type', '=', $db->raw(
        //         $db->connection()->getPdo()->quote($taskableType)
        //     ))
        //     ->orderBy('tasks.action_at', 'asc')
        //     ->orderBy('tasks.created_at', 'desc')
        //     ->select(['taskables.id', 'taskables.taskable_id']);

        // // Select only one task per taskable item
        // return $query
        //     ->join($db->raw("(
        //         select `id` from ({$ids->toSql()}) as `task_ids`
        //         group by `taskables.taskable_id`
        //     ) as `unique_task_ids`"), function ($join) {
        //         $join->on('unique_task_ids.id', '=', 'tasks.id');
        //     })
        //     ->select('tasks.*');
    }

}

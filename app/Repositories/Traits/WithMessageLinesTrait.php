<?php

namespace App\Repositories\Traits;

trait WithMessageLinesTrait
{
    /**
     * Select only the last message line sent.
     *
     * @param  MorphMany $query
     * @return MorphMany
     */
    protected function withLinesLast($query)
    {
        return $query
            ->whereIn('id', function ($_query) {
                $db = app('db');
                return $_query
                    ->select($db->raw('MAX(`id`)'))
                    ->from('message_lines')
                    ->groupBy('message_id');
            });
    }

    /**
     * Select only the first flagged messageline
     *
     * @param  MorphMany $query
     * @return MorphMany
     */
    protected function withLinesFlagged($query)
    {
        return $query
            ->whereIn('id', function ($_query) {
                $db = app('db');
                return $_query
                    ->select($db->raw('MAX(`id`)'))
                    ->from('message_lines')
                    ->where('flagged', '=', 1)
                    ->groupBy('message_id');
            });
    }

    protected function withLinesCount($query, $count)
    {
        return $query
            ->take($count)
            ->orderBy('id', 'DESC'); // Faster than created_at
    }
}

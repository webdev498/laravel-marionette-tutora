<?php

namespace App\Repositories\Traits;

use App\Lesson;
use App\LessonBooking;

trait WithRelationshipsLessonsTrait
{

    /**
     * Select only the lesson with a close lesson booking
     *
     * @param mixed $query
     * @return mixed
     */
    protected function withRelationshipsLessonsNext($query)
    {
        $db = app('db');

        $relationshipIds = $query->getQuery()->getBindings();

        $subQuery = $db->table('lessons')
            ->join('lesson_bookings', function ($join) {
                $join->on('lesson_bookings.lesson_id', '=', 'lessons.id');
            })
            ->whereIn('lessons.relationship_id', $relationshipIds)
            ->whereIn('lesson_bookings.status', LessonBooking::ACTIVE_STATUSES)
            ->orderBy('start_at', 'asc')
            ->select(['lessons.id', 'lessons.relationship_id']);

        foreach (LessonBooking::ACTIVE_STATUSES as $status) {
            $query->addBinding($status);
        }

        foreach ($relationshipIds as $relationshipId) {
            $query->addBinding($relationshipId);
        }

        return $query
            ->join($db->raw("(
                select `id` from ({$subQuery->toSql()}) as `lesson_ids`
                group by `relationship_id`
            ) as `unique_lesson_ids`"), function ($join) {
                $join->on('unique_lesson_ids.id', '=', 'lessons.id');
            })
            ->select('lessons.*');
    }

    /**
     * Select only the next lesson booking
     *
     * @param  mixed $query
     * @return mixed
     */
    protected function withRelationshipsLessonsBookingsNext($query)
    {
        $db = app('db');

        $lessonIds = $query->getQuery()->getBindings();

        $subQuery = $db->table('lesson_bookings')
            ->whereIn('lesson_id', $lessonIds)
            ->whereIn('status', LessonBooking::ACTIVE_STATUSES)
            ->orderBy('start_at', 'asc')
            ->select(['id', 'lesson_id']);

        foreach (LessonBooking::ACTIVE_STATUSES as $status) {
            $query->addBinding($status);
        }

        foreach ($lessonIds as $lessonId) {
            $query->addBinding($lessonId);
        }

        return $query
            ->join($db->raw("(
                select `id` from ({$subQuery->toSql()}) as `lesson_booking_ids`
                group by `lesson_id`
            ) as `unique_lesson_booking_ids`"), function ($join) {
                $join->on('unique_lesson_booking_ids.id', '=', 'lesson_bookings.id');
            })
            ->select('lesson_bookings.*');
    }

}

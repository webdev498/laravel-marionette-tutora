<?php namespace App\Repositories\Contracts;

use App\LessonSchedule;

interface LessonScheduleRepositoryInterface
{

    /**
     * Persist a lesson schedule to the database.
     *
     * @param  LessonSchedule $schedule
     * @return Lesson|null
     */
    public function save(LessonSchedule $schedule);

    /**
     * Remove a lesson schedule from the database.
     *
     * @param  LessonSchedule $schedul
     * @return boolean
     */
    public function delete(LessonSchedule $schedule);

}

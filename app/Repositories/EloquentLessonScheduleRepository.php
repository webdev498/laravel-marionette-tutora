<?php namespace App\Repositories;

use App\Repositories\Contracts\LessonScheduleRepositoryInterface;
use Illuminate\Database\DatabaseManager as Database;
use App\LessonSchedule;

class EloquentLessonScheduleRepository implements LessonScheduleRepositoryInterface
{

    /*
     * @var Database
     */
    protected $database;

    /**
     * @var LessonSchedule
     */
    protected $schedule;

    /**
     * Create the repository.
     *
     * @param  Database       $database
     * @param  LessonSchedule $schedule
     * @return void
     */
    public function __construct(Database $database, LessonSchedule $schedule)
    {
        $this->database = $database;
        $this->schedule = $schedule;
    }

    /**
     * Persist a lesson schedule to the database.
     *
     * @param  LessonSchedule $schedule
     * @return Lesson|null
     */
    public function save(LessonSchedule $schedule)
    {
        if ( ! $schedule->save()){
            throw new ResourceNotPersistedException();
        }

        return $schedule;
    }

    /**
     * Remove a lesson schedule from the database.
     *
     * @param  LessonSchedule $schedul
     * @return boolean
     */
    public function delete(LessonSchedule $schedule)
    {
        if ( ! $schedule->delete()) {
            throw new \Exception('Resource not deleted');
        }

        return true;
    }
}

<?php namespace App\Events;

use App\Events\Event;
use App\LessonSchedule;
use Illuminate\Queue\SerializesModels;

class LessonScheduleWasMade extends Event
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  LessonSchedule $schedule
     * @return void
     */
    public function __construct(LessonSchedule $schedule)
    {
        $this->schedule = $schedule;
    }

}

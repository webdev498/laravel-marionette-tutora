<?php namespace App\Events;

use App\Lesson;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class LessonWasBooked extends Event
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Lesson $lesson
     * @return void
     */
    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

}

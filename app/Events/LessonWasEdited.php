<?php namespace App\Events;

use App\Lesson;
use App\Events\Event;
use App\LessonBooking;
use Illuminate\Queue\SerializesModels;

class LessonWasEdited extends Event
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

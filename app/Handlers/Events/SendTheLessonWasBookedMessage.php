<?php namespace App\Handlers\Events;

use App\Events\LessonWasBooked;
use App\Commands\SendMessageCommand;
use Illuminate\Foundation\Bus\DispatchesCommands;

class SendTheLessonWasBookedMessage extends EventHandler
{

    use DispatchesCommands;

    /**
     * Handle the event.
     *
     * @param  LessonWasBooked $event
     * @return void
     */
    public function handle(LessonWasBooked $event)
    {
        $lesson       = $event->lesson;
        $relationship = $lesson->relationship;

        $data = [
            'lesson_id' => $lesson->id
        ];

        $array = [
            'relationship' => $relationship,
            'body'        => '>LESSON_WAS_BOOKED '.json_encode($data),
            'from_system' => true,
            'silent'      => true,
        ];

        $line = $this->dispatchFromArray(SendMessageCommand::class, $array);

        return $line;
    }

}

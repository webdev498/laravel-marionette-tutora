<?php namespace App\Handlers\Events;

use App\Events\LessonWasEdited;
use App\Commands\SendMessageCommand;
use Illuminate\Foundation\Bus\DispatchesCommands;

class SendTheLessonWasEditedMessage extends EventHandler
{

    use DispatchesCommands;

    /**
     * Handle the event.
     *
     * @param  LessonWasEdited $event
     * @return void
     */
    public function handle(LessonWasEdited $event)
    {
        $booking = $event->booking;
        $lesson  = $booking->lesson;

        $data = [
            'lesson_id'  => $lesson->id,
            'booking_id' => $booking->id
        ];

        $array = [
            'body'        => '>LESSON_WAS_EDITED '.json_encode($data),
            'to'          => [$lesson->tutor->uuid, $lesson->student->uuid],
            'from_system' => true,
            'silent'      => true,
        ];

        $line = $this->dispatchFromArray(SendMessageCommand::class, $array);

        return $line;
    }

}

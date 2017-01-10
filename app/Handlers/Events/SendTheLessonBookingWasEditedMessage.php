<?php namespace App\Handlers\Events;

use App\Commands\SendMessageCommand;
use App\Events\LessonBookingWasEdited;
use Illuminate\Foundation\Bus\DispatchesCommands;

class SendTheLessonBookingWasEditedMessage extends EventHandler
{

    use DispatchesCommands;

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasEdited $event
     * @return void
     */
    public function handle(LessonBookingWasEdited $event)
    {
        $booking      = $event->booking;
        $lesson       = $booking->lesson;
        $relationship = $lesson->relationship;

        $data = [
            'booking_id' => $booking->id
        ];

        $array = [
            'body'         => '>LESSON_WAS_EDITED '.json_encode($data),
            'relationship' => $relationship,
            'from_system'  => true,
            'silent'       => true,
        ];

        $line = $this->dispatchFromArray(SendMessageCommand::class, $array);

        return $line;
    }

}

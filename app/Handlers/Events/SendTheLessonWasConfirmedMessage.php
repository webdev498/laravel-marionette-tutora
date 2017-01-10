<?php namespace App\Handlers\Events;

use App\Events\LessonWasConfirmed;
use App\Commands\SendMessageCommand;
use Illuminate\Foundation\Bus\DispatchesCommands;

class SendTheLessonWasConfirmedMessage extends EventHandler
{

    use DispatchesCommands;

    /**
     * Handle the event.
     *
     * @param  LessonWasConfirmed $event
     * @return void
     */
    public function handle(LessonWasConfirmed $event)
    {
        // Lookups
        $lesson       = $event->lesson;
        $relationship = $lesson->relationship;
        // Data
        $data = [
            'lesson_id' => $lesson->id
        ];
        // Data data
        $array = [
            'body'         => '>LESSON_WAS_CONFIRMED '.json_encode($data),
            'relationship' => $relationship,
            'from_system'  => true,
            'silent'       => true,
        ];
        // Dispatch
        $line = $this->dispatchFromArray(SendMessageCommand::class, $array);
        // Return
        return $line;
    }

}

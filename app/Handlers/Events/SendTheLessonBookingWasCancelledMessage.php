<?php namespace App\Handlers\Events;

use App\Commands\SendMessageCommand;
use App\Events\LessonBookingWasCancelled;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Foundation\Bus\DispatchesCommands;

class SendTheLessonBookingWasCancelledMessage extends EventHandler
{

    use DispatchesCommands;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * Create the event handler
     *
     * @param  Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasCancelled $event
     * @return void
     */
    public function handle(LessonBookingWasCancelled $event)
    {
        $user         = $this->auth->user();
        $booking      = $event->booking;
        $lesson       = $booking->lesson;
        $relationship = $lesson->relationship;

        $data = [
            'booking_id' => $booking->id,
            'lesson_id'  => $lesson->id,
            'user_id'    => $user->id,
        ];

        $array = [
            'body'         => '>LESSON_WAS_CANCELLED '.json_encode($data),
            'relationship' => $relationship,
            'from_system'  => true,
            'silent'       => true,
        ];

        $line = $this->dispatchFromArray(SendMessageCommand::class, $array);
        return $line;
    }

}

<?php namespace App\Handlers\Events;

use App\LessonBooking;
use App\Exceptions\AppException;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\ReminderRepositoryInterface;

class RemoveLessonBookingReminders extends EventHandler
{
    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var ReminderRepositoryInterface
     */
    protected $reminders;

    /**
     * Create an instance of this
     *
     * @param  DatabaseManager             $database
     * @param  ReminderRepositoryInterface $reminders
     * @return void
     */
    public function __construct(
        DatabaseManager             $database,
        ReminderRepositoryInterface $reminders
    ) {
        $this->database  = $database;
        $this->reminders = $reminders;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasCancelled $event
     * @return void
     */
    public function handle($event)
    {
        loginfo('Called Remove Lesson Booking Reminders');
        if ( ! $event->booking || ! ($event->booking instanceof LessonBooking)) {
            throw new AppException('No LessonBooking provided');
        }

        $this->database->transaction(function () use ($event) {
            $booking = $event->booking;

            foreach ($booking->reminders as $reminder) {
                $this->reminders->delete($reminder);
            }
        });
        loginfo('Finished Remove Lesson Booking Reminders');
    }

}

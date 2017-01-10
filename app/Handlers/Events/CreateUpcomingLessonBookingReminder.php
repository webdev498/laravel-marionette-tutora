<?php

namespace App\Handlers\Events;

use App\Reminder;
use Carbon\Carbon;
use App\LessonBooking;
use App\Events\LessonBookingWasMade;
use App\Repositories\Contracts\ReminderRepositoryInterface;

class CreateUpcomingLessonBookingReminder extends EventHandler
{

    /**
     * @var ReminderRepositoryInterface
     */
    protected $reminders;

    /**
     * Create the event handler.
     *
     * @param  ReminderRepositoryInterface $reminders
     * @return void
     */
    public function __construct(ReminderRepositoryInterface   $reminders)
    {
        $this->reminders = $reminders;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasMade $event
     * @return void
     */
    public function handle(LessonBookingWasMade $event)
    {
        $booking  = $event->booking;
        $remindAt = $this->getDate($booking);

        if ($remindAt) {
            $reminder = Reminder::make(Reminder::UPCOMING, $remindAt);
            $this->reminders->saveForLessonBooking($booking, $reminder);
        }
    }

    /**
     * Get the remind at date for the given booking
     *
     * @param  LessonBooking $booking
     * @return DateTime|null
     */
    protected function getDate(LessonBooking $booking)
    {
        $now      = Carbon::now();
        $minutes  = config('lessons.reminder_upcoming_period', -1440);
        $remindAt = $booking->start_at->copy()->addMinutes($minutes);

        if ($remindAt->gt($now)) {
            return $remindAt;
        }
    }

}

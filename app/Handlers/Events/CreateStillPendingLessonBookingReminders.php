<?php

namespace App\Handlers\Events;

use App\Reminder;
use Carbon\Carbon;
use App\LessonBooking;
use App\Events\LessonBookingWasMade;
use App\Repositories\Contracts\ReminderRepositoryInterface;

class CreateStillPendingLessonBookingReminders extends EventHandler
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
        $booking = $event->booking;

        if ($booking->status !== LessonBooking::CONFIRMED) {
            $firstReminderDate = $this->getFirstReminderDate($booking);
            $secondReminderDate = $this->getSecondReminderDate($booking);

            if ($firstReminderDate) {
                $reminder = Reminder::make(Reminder::STILL_PENDING, $firstReminderDate);
                $this->reminders->saveForLessonBooking($booking, $reminder);
            }
            if ($secondReminderDate) {
                $reminder = Reminder::make(Reminder::STILL_PENDING, $secondReminderDate);
                $this->reminders->saveForLessonBooking($booking, $reminder);
            }           
        }
    }

    /**
     * Get the first remind at date for the given booking
     *
     * @param  LessonBooking $booking
     * @return DateTime|null
     */
    protected function getFirstReminderDate(LessonBooking $booking)
    {
        
        $minutes  = config('lessons.first_reminder_still_pending_period', -2880);

        return $this->getReminderDate($booking, $minutes);
    }

    /**
     * Get the first remind at date for the given booking
     *
     * @param  LessonBooking $booking
     * @return DateTime|null
     */
    protected function getSecondReminderDate(LessonBooking $booking)
    {
        $minutes  = config('lessons.second_reminder_still_pending_period', -1440);

        return $this->getReminderDate($booking, $minutes);
    }

    /**
     * Get the remind at date for the given booking
     *
     * @param  LessonBooking $booking
     * @return DateTime|null
     */
    protected function getReminderDate(LessonBooking $booking, $minutes)
    {
        $now      = Carbon::now();
        $remindAt = $booking->start_at->copy()->addMinutes($minutes);

        if ($remindAt->gt($now)) {
            return $remindAt;
        }
    }
}

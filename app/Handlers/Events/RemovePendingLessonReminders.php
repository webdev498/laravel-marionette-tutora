<?php

namespace App\Handlers\Events;

use App\Reminder;
use App\LessonBookingReminder;
use App\Events\LessonWasConfirmed;
use App\Events\UserReviewWasLeft;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\Contracts\RelationshipRepositoryInterface;
use App\Repositories\Contracts\ReminderRepositoryInterface;

class RemovePendingLessonReminders extends EventHandler
{

    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;

    /**
     * @var LessonRepositoryInterface
     */
    protected $lessons;

    /**
     * @var ReminderRepositoryInterface
     */
    protected $reminders;

    /**
     * Create an instance of the event handler.
     *
     * @param  RelationshipRepositoryInterface $relationships
     * @param  ReminderRepositoryInterface     $reminders
     * @return void
     */
    public function __construct(
        RelationshipRepositoryInterface $relationships,
        LessonRepositoryInterface       $lessons,
        ReminderRepositoryInterface     $reminders
    ) {
        $this->relationships = $relationships;
        $this->lessons       = $lessons;
        $this->reminders     = $reminders;
    }

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
        if ($relationship) {
            // Load
            $relationship->load([
                'lessons',
                'lessons.bookings',
                'lessons.bookings.reminders'
            ]);
            // Delete
            foreach ($relationship->lessons as $lesson) {
                foreach ($lesson->bookings as $booking) {
                    foreach ($booking->reminders as $reminder) {
                        if ($reminder->name === Reminder::STILL_PENDING) {
                            
                            $this->reminders->delete($reminder);
                        }
                    }
                }
            }
        }
    }

}

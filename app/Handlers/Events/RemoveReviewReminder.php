<?php

namespace App\Handlers\Events;

use App\Reminder;
use App\UserReview;
use App\LessonBookingReminder;
use App\Events\UserReviewWasLeft;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use App\Repositories\Contracts\RelationshipRepositoryInterface;

class RemoveReviewReminder extends EventHandler
{

    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;

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
        ReminderRepositoryInterface     $reminders
    ) {
        $this->relationships = $relationships;
        $this->reminders     = $reminders;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingReviewWasLeft $event
     * @return void
     */
    public function handle(UserReviewWasLeft $event)
    {
        // Lookups
        $review       = $event->review;
        $relationship = $this->findRelationship($review);
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
                        if ($reminder->name === Reminder::REVIEW) {
                            $this->reminders->delete($reminder);
                        }
                    }
                }
            }
        }
    }

    /**
     * Find a relationship between the tutor and student of a given review.
     *
     * @param  UserReview $review
     * @return Relationship
     */
    protected function findRelationship(UserReview $review)
    {
        $tutor   = $review->user;
        $student = $review->reviewer;

        return $this->relationships->findByTutorAndStudent($tutor, $student);
    }

}

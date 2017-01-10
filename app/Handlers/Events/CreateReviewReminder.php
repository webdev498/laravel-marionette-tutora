<?php namespace App\Handlers\Events;

use App\Reminder;
use App\LessonBooking;
use App\Events\LessonBookingWasCompleted;
use App\Repositories\Contracts\ReminderRepositoryInterface;
use App\Repositories\Contracts\UserReviewRepositoryInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class CreateReviewReminder extends EventHandler
{

    /**
     * @var UserReviewRepositoryInterface
     */
    protected $reviews;

    /**
     * @var ReminderRepositoryInterface
     */
    protected $reminders;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * Create the event handler.
     *
     * @param  UserReviewRepositoryInterface    $reviews
     * @param  ReminderRepositoryInterface      $reminders
     * @param  LessonBookingRepositoryInterface $bookings
     */
    public function __construct(
        UserReviewRepositoryInterface      $reviews,
        ReminderRepositoryInterface        $reminders,
        LessonBookingRepositoryInterface   $bookings
    ) {
        $this->reviews   = $reviews;
        $this->reminders = $reminders;
        $this->bookings  = $bookings;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasCompleted $event
     * @return void
     */
    public function handle(LessonBookingWasCompleted $event)
    {
        $booking      = $event->booking;
        $lesson       = $booking->lesson;
        $relationship = $lesson->relationship;
        $tutor        = $relationship->tutor;
        $student      = $relationship->student;

        $isNoReviews = $this->reviews->countByStudentForTutor($student, $tutor) < 1;
        $isRequiredLessonsCount = $this->bookings->countByRelationshipAndStatus($relationship, LessonBooking::COMPLETED) > 1;

        if ($isNoReviews && $isRequiredLessonsCount) {
            $remindAt = $this->getDate($booking);
            $reminder = Reminder::make(Reminder::REVIEW, $remindAt);
            $this->reminders->saveForLessonBooking($booking, $reminder);
        }
    }

    /**
     * Get the date that we'll send the reminder at
     *
     * @param  LessonBooking $booking
     * @return DateTime
     */
    protected function getDate(LessonBooking $booking)
    {
        $minutes = config('lessons.reminder_review_period', 1440);
        return $booking->finish_at->copy()->addMinutes($minutes);
    }
}

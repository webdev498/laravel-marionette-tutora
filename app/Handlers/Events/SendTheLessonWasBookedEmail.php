<?php namespace App\Handlers\Events;

use App\Lesson;
use App\LessonBooking;
use App\Events\LessonWasBooked;
use App\Mailers\StudentsLessonMailer;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class SendTheLessonWasBookedEmail extends EventHandler
{

    /**
     * @var StudentsLessonMailer
     */
    protected $mailer;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * Create the event handler.
     *
     * @param  StudentsLessonMailer             $mailer
     * @param  LessonBookingRepositoryInterface $bookings
     * @return void
     */
    public function __construct(
        StudentsLessonMailer             $mailer,
        LessonBookingRepositoryInterface $bookings
    ) {
        $this->mailer   = $mailer;
        $this->bookings = $bookings;
    }

    /**
     * Handle the event.
     *
     * @param  LessonWasBooked $event
     * @return void
     */
    public function handle(LessonWasBooked $event)
    {
        $lesson       = $event->lesson;
        $relationship = $lesson->relationship;
        $student      = $relationship->student;
        $booking      = $lesson->bookings->first();

        if ($booking->status === LessonBooking::CONFIRMED) {
            $this->mailer->lessonAutoBooked($student, $relationship, $lesson, $booking);
        } else {
            $this->mailer->lessonBooked($student, $relationship, $lesson, $booking);
        }
    }

}

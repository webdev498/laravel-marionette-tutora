<?php namespace App\Handlers\Events;

use App\Lesson;
use App\LessonBooking;
use App\Events\LessonWasBooked;
use App\Twilio\StudentTwilio;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class SendTheLessonWasBookedText extends EventHandler 
{

    /**
     * @var 
     */
    protected $twilio;

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
        StudentTwilio                       $twilio,
        LessonBookingRepositoryInterface    $bookings
    ) {
        $this->twilio   = $twilio;
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
        $twilio       = $this->twilio;

        if ($booking->status !== LessonBooking::CONFIRMED) {
            
            $this->twilio->lessonBooked($student, $relationship, $lesson, $booking);

        } 
    }

}
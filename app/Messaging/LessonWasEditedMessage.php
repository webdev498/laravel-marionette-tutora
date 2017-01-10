<?php namespace App\Messaging;

use App\Lesson;
use App\MessageLine;
use App\Messaging\Contracts\MessageInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class LessonWasEditedMessage implements MessageInterface
{

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * Create an instance of the message
     *
     * @param  LessonBookingRepositoryInterface $bookings
     * @return void
     */
    public function __construct(LessonBookingRepositoryInterface $bookings)
    {
        $this->bookings = $bookings;
    }

    /**
     * Present the message line
     *
     * @param  MessageLine $line
     * @param  Array       $data
     * @return String
     */
    public function present(MessageLine $line, $data)
    {
        $booking = $this->bookings
            ->with([
                'lesson',
                'lesson.relationship',
                'lesson.subject',
                'lesson.relationship.tutor'
            ])
            ->findById($data->booking_id);

        if ($booking === null) {
            $body = 'A lesson was edited, but, the record has since been removed.';
        } else {
            $lesson       = $booking->lesson;
            $subject      = $lesson->subject;
            $relationship = $lesson->relationship;
            $tutor        = $relationship->tutor;

            $body = sprintf(
                '<strong>%s</strong> has edited a lesson in <strong>%s</strong>.',
                $tutor->first_name,
                $subject->title
            );
        }

        return '<p>'.$body.'</p>';
    }

}

<?php namespace App\Messaging;

use App\Lesson;
use App\LessonBooking;
use App\MessageLine;
use App\Messaging\Contracts\MessageInterface;
use App\Presenters\LessonBookingPresenter;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class LessonWasCancelledMessage extends AbstractMessage implements MessageInterface 
{

    /**
     * @var LessonRepositoryInterface
     */
    protected $lessons;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * Create an instance of the message
     *
     * @param  UserRepositoryInterface          $users
     * @param  LessonRepositoryInterface        $lessons
     * @param  LessonBookingRepositoryInterface $bookings
     * @return void
     */
    public function __construct(
        UserRepositoryInterface          $users,
        LessonRepositoryInterface        $lessons,
        LessonBookingRepositoryInterface $bookings
    ) {
        $this->users    = $users;
        $this->lessons  = $lessons;
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
        $lesson = $this->lessons->findById($data->lesson_id);
        $relationship = $lesson->relationship;
        $booking = $this->bookings->findById($data->booking_id);
        $nextBooking = $this->bookings->getByRelationshipAndStatus($relationship, [LessonBooking::CONFIRMED, LessonBooking::PENDING])->first();

        if ($lesson === null) {
            $body = 'A lesson was cancelled, but, the record has since been removed.';
        } else {
            $subject      = $lesson->subject;
            $relationship = $lesson->relationship;
            $user         = $this->users->findById($data->user_id);
            
            $body = sprintf(
                '<strong>%s</strong> has cancelled a lesson in <strong>%s</strong>',
                $user ? $user->first_name : 'Someone',
                $subject->title
            );

            if ($booking instanceof LessonBooking) {
                $booking = $this->presentItem($booking, new LessonBookingPresenter());
                $body .= ', scheduled for ' . $booking->date->short .'.';
            }

            if ($nextBooking instanceof LessonBooking) {
                $nextBooking = $this->presentItem($nextBooking, new LessonBookingPresenter());
                $body .= ' The next lesson is on <strong>' . $nextBooking->date->short . '</strong>';
            } else {
                $body .= ' There are no more lessons booked';
            }

        }

        return '<p>'.$body.'</p>';
    }

}

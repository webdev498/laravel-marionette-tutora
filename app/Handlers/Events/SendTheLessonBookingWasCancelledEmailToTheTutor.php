<?php namespace App\Handlers\Events;

use App\Mailers\TutorsLessonMailer;
use App\Events\LessonBookingWasCancelled;
use App\Relationship;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\Eloquent\Collection;
use App\LessonBooking;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class SendTheLessonBookingWasCancelledEmailToTheTutor extends EventHandler
{

    const NEXT_BOOKINGS_LIMIT = 4;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var TutorsLessonMailer
     */
    protected $mailer;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * Create the event handler.
     *
     * @param  Auth               $auth
     * @param  TutorsLessonMailer $mailer
     * @param  LessonBookingRepositoryInterface $bookings
     */
    public function __construct(
        Auth               $auth,
        TutorsLessonMailer $mailer,
        LessonBookingRepositoryInterface $bookings
    ) {
        $this->auth     = $auth;
        $this->mailer   = $mailer;
        $this->bookings = $bookings;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasCancelled $event
     * @return void
     */
    public function handle(LessonBookingWasCancelled $event)
    {

        $booking      = $event->booking;
        $lesson       = $booking->lesson;
        $relationship = $lesson->relationship;
        $tutor        = $relationship->tutor;
        $user         = $this->auth->user();

        if ($user && $user->id === $tutor->id) {
            return;
        }

        $nextBookings = $this->getNextBookings($relationship);

        $this->mailer->lessonCancelled($tutor, $relationship, $lesson, $booking, $nextBookings);
    }

    /**
     * @param Relationship $relationship
     *
     * @return Collection
     */
    protected function getNextBookings(Relationship $relationship)
    {
        $this->bookings->paginate(1, self::NEXT_BOOKINGS_LIMIT);
        $items = $this->bookings->getByRelationshipAndStatus($relationship, [LessonBooking::CONFIRMED, LessonBooking::UPCOMING]);

        return $items;
    }

}

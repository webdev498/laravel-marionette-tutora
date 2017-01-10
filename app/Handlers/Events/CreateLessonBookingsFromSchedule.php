<?php namespace App\Handlers\Events;

use App\LessonBooking;
use App\LessonSchedule;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\LessonRepositoryInterface;

class CreateLessonBookingsFromSchedule extends EventHandler
{

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var LessonRepositoryInterface
     */
    protected $lessons;

    /**
     * Create the event handler.
     *
     * @param  DatabaseManager              $database
     * @param  LessonRepositoryInterface    $lessons
     * @return void
     */
    public function __construct(
        DatabaseManager              $database,
        LessonRepositoryInterface    $lessons
    ) {
        $this->database = $database;
        $this->lessons  = $lessons;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingWasCompleted $event
     * @return void
     */
    public function handle($event)
    {
        $this->database->transaction(function () use ($event) {
            $booking = $event->booking;

            if ( ! $booking) {
                throw new Exception('Booking not found');
            }

            $lesson   = $booking->lesson;
            $schedule = $lesson->schedule;

            if ( ! $schedule) {
                return;
            }

            $bookings = $lesson->bookings()
                ->whereNotIn('status', [LessonBooking::COMPLETED, LessonBooking::CANCELLED])
                ->get();

            $start = $schedule->last_scheduled_at;
            $count = config('booking.repeat.count', 10) - $bookings->count();

            if ($count > 0) {
                $dates = $schedule->dates($start, $count, false);

                // Bookings
                $bookings = [];

                foreach ($dates as $_start) {
                    do {
                        $uuid = str_uuid();
                    } while (LessonBooking::where('uuid', '=', $uuid)->count() > 0);

                    $bookings[] = LessonBooking::make(
                        $uuid,
                        $_start,
                        $lesson
                    );
                }

                if ($schedule !== null) {
                    LessonSchedule::updateLastScheduledAt(
                        $schedule,
                        last($bookings)->start_at->copy()
                    );
                }

                $this->lessons->save($lesson, $bookings, $schedule);

                // Schedule might be null, don't release events on it.
                $this->dispatchFor(array_filter([$lesson, $schedule, $bookings]));
            }
        });
    }
}

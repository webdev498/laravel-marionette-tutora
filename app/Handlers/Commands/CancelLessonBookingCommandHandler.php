<?php namespace App\Handlers\Commands;

use DateTime;
use App\User;
use App\Lesson;
use App\LessonBooking;
use App\LessonSchedule;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\DatabaseManager;
use App\Commands\CancelLessonBookingCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Validators\CancelLessonBookingValidator;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Repositories\Contracts\LessonScheduleRepositoryInterface;

class CancelLessonBookingCommandHandler extends CommandHandler
{

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var CancelLessonBookingValidator
     */
    protected $validator;

    /**
     * @var LessonRepositoryInterface
     */
    protected $lessons;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * @var LessonScheduleRepositoryInterface
     */
    protected $schedules;

    /**
     * Create the command handler.
     *
     * @param  DatabaseManager                   $database
     * @param  CancelLessonBookingValidator      $validator
     * @param  LessonRepositoryInterface         $lesson
     * @param  LessonBookingRepositoryInterface  $bookings
     * @param  LessonScheduleRepositoryInterface $schedules
     * @return void
     */
    public function __construct(
        DatabaseManager                   $database,
        Auth                              $auth,
        CancelLessonBookingValidator      $validator,
        LessonRepositoryInterface         $lessons,
        LessonBookingRepositoryInterface  $bookings,
        LessonScheduleRepositoryInterface $schedules
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->validator = $validator;
        $this->lessons   = $lessons;
        $this->bookings  = $bookings;
        $this->schedules = $schedules;
    }

    /**
     * Handle the command.
     *
     * @param  CancelLessonBookingCommand  $command
     * @return void
     */
    public function handle(CancelLessonBookingCommand $command)
    {
        return $this->database->transaction(function () use ($command) {

            // Validate
            $this->guardAgainstInvalidData($command);
            // Lookups
            $user    = $this->auth->user();
            $booking = $this->findBooking($command->uuid);
            // Data
            $future = $command->future === "true" || $command->future === true;
            // Guard
            $this->guardAgainstUserNotPermitted($booking, $user);

            if ($future === true) {
                $lesson   = $booking->lesson;
                $schedule = $lesson->schedule;
                // Delete
                $this->deleteFutureBookings($lesson, $booking->start_at);
                $this->deleteSchedule($schedule);
            }
            // Cancel
            LessonBooking::cancel($booking);
            // Save
            $this->bookings->save($booking);
            // Dispatch
            $this->dispatchFor($booking);
            return $booking;
        });
    }

    /**
     * Delete lesson bookings for a given lesson after a given date
     *
     * @param  Lesson   $lesson
     * @param  DateTime $date
     * @return void
     */
    protected function deleteFutureBookings(Lesson $lesson, DateTime $date)
    {
        $this->bookings->deleteByLessonAndStartAfterDate($lesson, $date);
    }

    /**
     * Delete a given lesson schedule
     *
     * @param  LessonSchedule|null
     * @return void
     */
    protected function deleteSchedule(LessonSchedule $schedule = null)
    {
        if ($schedule) {
            $this->schedules->delete($schedule);
        }
    }

    /**
     * Find a booking by a given uuid
     *
     * @param  string $uuid
     * @return LessonBooking
     */
    protected function findBooking($uuid)
    {
        $booking = $this->bookings->findByUuid($uuid);

        if ( ! $booking) {
            throw new ResourceNotFoundException();
        }

        return $booking;
    }

    /**
     * Guard against invalid data on the command.
     *
     * @param  CancelLessonBookingCommand $command
     * @return void
     */
    protected function guardAgainstInvalidData(CancelLessonBookingCommand $command)
    {
        return $this->validator->validate((array) $command);
    }

    /**
     * Guard against a user not having permission to cancel a booking.
     *
     * @param  LessonBooking $booking
     * @param  User          $user
     * @return void
     */
    protected function guardAgainstUserNotPermitted(LessonBooking $booking, User $user)
    {
        
        if (
            $booking->lesson->relationship->tutor->id   !== $user->id &&
            $booking->lesson->relationship->student->id !== $user->id 
        ) {
            if (! $user->isAdmin()) {
                throw new UnauthorizedException();
            }
        }
    }

}

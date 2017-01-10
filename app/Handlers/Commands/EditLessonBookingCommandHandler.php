<?php namespace App\Handlers\Commands;

use App\User;
use DateTime;
use App\Lesson;
use Carbon\Carbon;
use App\LessonBooking;
use App\LessonSchedule;
use App\Exceptions\AppException;
use Illuminate\Support\Collection;
use Illuminate\Database\DatabaseManager;
use App\Commands\EditLessonBookingCommand;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Validators\EditLessonBookingValidator;
use App\Auth\Exceptions\UnauthorizedException;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class EditLessonBookingCommandHandler extends CommandHandler
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
     * @var EditLessonBookingValidator
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
     * Create the command handler.
     *
     * @param  DatabaseManager                  $database
     * @param  Auth                             $auth
     * @param  EditLessonBookingValidator       $validator
     * @param  LessonRepositoryInterface        $lesson
     * @param  LessonBookingRepositoryInterface $bookings
     * @return void
     */
    public function __construct(
        DatabaseManager                   $database,
        Auth                              $auth,
        EditLessonBookingValidator        $validator,
        LessonRepositoryInterface         $lessons,
        LessonBookingRepositoryInterface  $bookings
    ) {
        $this->database  = $database;
        $this->auth      = $auth;
        $this->validator = $validator;
        $this->lessons   = $lessons;
        $this->bookings  = $bookings;
    }

    /**
     * Handle the command.
     *
     * @param  EditLessonBookingCommand $command
     * @return LessonBooking
     */
    public function handle(EditLessonBookingCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            // Validate
            $this->guardAgainstInvalidData($command);
            // Lookups
            $user    = $this->auth->user();
            $booking = $this->findBooking($command->uuid);
            // Guard
            $this->guardAgainstUserNotAllowedToEdit($booking, $user);
            // Data
            $start    = strtodate($command->date.' '.$command->time);
            $duration = (integer) strtoseconds($command->duration);
            $rate     = $command->rate ? (integer) $command->rate : (integer) $booking->lesson->relationship->tutor->profile->rate;
            $location = (string)  $command->location;
            $future   = $command->future === "true" || $command->future === true;

            if ($future === true) {
                $lesson   = $booking->lesson;
                $schedule = $lesson->schedule;
                // Edit lesson
                $lesson = $this->dispatch[] = Lesson::edit($lesson, $duration, $rate, $location);
                // Lookup bookings to edit
                $bookings = $this->findFutureBookings($lesson, $booking->start_at);
                // Edit schedule & find next dates
                list($schedule, $dates) = $this->editSchedule(
                    $schedule,
                    $start,
                    count($bookings)
                );
                // Edit bookings
                $bookings = $this->editBookings($bookings, $dates, $duration, $rate, $location);
                // Save
                $lesson = $this->lessons->save($lesson, $bookings, $schedule);
                // Queue
            } else {
                $lesson   = $booking->lesson;

                $lesson = Lesson::edit($lesson, $duration, $rate, $location);

                $lesson = $this->lessons->save($lesson);

                // Edit booking
                $booking = $this->dispatch[] = LessonBooking::edit($booking, $start, $duration, $rate, $location);
                // Save
                $booking = $this->bookings->save($booking);
            }
            // Dispatch
            $this->dispatchFor($this->dispatch);
            // Return
            return $booking;
        });
    }

    /**
     * Edit a lesson schedule with the date, return a $count of dates
     *
     * @param  LessonSchedule $schedule
     * @param  DateTime       $start
     * @param  integer        $count the number of dates to return
     * @return array          ['schedule', 'dates']
     */
    protected function editSchedule(LessonSchedule $schedule, DateTime $start, $count)
    {
        $schedule = LessonSchedule::edit($schedule, $start);
        $dates    = $schedule->dates($start, $count);
        $schedule = LessonSchedule::updateLastScheduledAt($schedule, last($dates));

        return [$schedule, $dates];
    }

    /**
     * Edit the given booking
     *
     * @param Collection $bookings
     * @param Array      $dates
     * @param string     $location
     */
    protected function editBookings(Collection $bookings, Array $dates, $duration, $rate, $location)
    {
        return $bookings->map(function ($booking) use (&$dates, $duration, $rate, $location) {
            $date = array_shift($dates);
            return LessonBooking::edit($booking, $date, $duration, $rate, $location);
        });
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
     * Find future bookings by a given lesson and date
     *
     * @param  Lesson   $lesson
     * @param  DateTime $date
     * @return Collection
     */
    protected function findFutureBookings(Lesson $lesson, DateTime $date)
    {
        return $this->bookings->getByLessonAndStartAfterDate($lesson, $date, true);
    }

    /**
     * Guard against invalid data on the command.
     *
     * @param  EditLessonBookingCommand $command
     * @return void
     */
    protected function guardAgainstInvalidData(EditLessonBookingCommand $command)
    {
        return $this->validator->validate((array) $command);
    }

    /**
     * Guard against a user not having permission to edit a booking.
     *
     * @param  LessonBooking $booking
     * @param  User          $user
     * @return void
     */
    protected function guardAgainstUserNotAllowedToEdit(LessonBooking $booking, User $user)
    {
        if ($booking->lesson->relationship->tutor->id !== $user->id) {
            
            if (! $user->isAdmin()) {
                throw new UnauthorizedException();
            }
        }
    }

}

<?php namespace App\Handlers\Commands;

use App\Auth\Exceptions\UnauthorizedException;
use App\Billing\Contracts\BillingInterface;
use App\Billing\Exceptions\BillingException;
use App\Commands\ConfirmLessonBookingCommand;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Exceptions\AppException;
use App\Lesson;
use App\LessonBooking;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\User;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager;

class ConfirmLessonBookingCommandHandler extends CommandHandler
{

    /**
     * An array of objects to dispatch events off.
     *
     * @var array
     */
    protected $dispatch = [];

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var LessonRepositoryInterface
     */
    protected $lessons;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * @var BillingInterface
     */
    protected $billing;

    /**
     * Create the command handler.
     *
     * @param  DatabaseManager                  $database
     * @param  Auth                             $auth
     * @param  LessonRepositoryInterface        $lessons
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  UserRepositoryInterface          $users
     * @param  BillingInterface                 $billing
     * @return void
     */
    public function __construct(
        DatabaseManager                  $database,
        Auth                             $auth,
        LessonRepositoryInterface        $lessons,
        LessonBookingRepositoryInterface $bookings,
        UserRepositoryInterface          $users,
        BillingInterface                 $billing
    ) {
        $this->database = $database;
        $this->auth     = $auth;
        $this->lessons  = $lessons;
        $this->bookings = $bookings;
        $this->users    = $users;
        $this->billing  = $billing;
    }

    /**
     * Handle the command.
     *
     * @param  ConfirmLessonBookingCommand  $command
     * @return void
     */
    public function handle(ConfirmLessonBookingCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            // Validation
            $this->guardAgainstInvalidData($command);
            // Lookups
            $user    = $this->auth->user();
            $booking = $this->findBooking($command->uuid);
            // Guard
            $this->guardAgainstUserNotPermitted($booking, $user);
            // Card
            if ($command->card) {
                $user = $this->dispatch[] = $this->updateCard($user, $command);
                // Save
                if ($user) {
                    $this->users->save($user);
                }
            }
            // Lookups
            $lesson       = $booking->lesson;
            $relationship = $lesson->relationship;
            $lessons      = $relationship->lessons;
            // Lessons
            foreach ($lessons as $lesson) {
                if ($lesson->status === Lesson::PENDING) {
                    // Lesson
                    $lesson = $this->dispatch[] = Lesson::confirm($lesson);
                    $this->lessons->save($lesson);
                    // Bookings
                    foreach ($lesson->bookings as $booking) {
                        if ($booking->status === LessonBooking::PENDING) {
                            $booking = $this->dispatch[] = LessonBooking::confirm($booking);
                            $this->bookings->save($booking);
                        }
                    }
                }
            }
            // Dispatch
            $this->dispatchFor(array_filter($this->dispatch));
            // Return
            return $booking;
        });
    }

    /**
     * Update a users card
     *
     * @param  User                        $user
     * @param  ConfirmLessonBookingCommand $command
     * @return User
     */
    protected function updateCard(
        User                        $user,
        ConfirmLessonBookingCommand $command
    ) {
        try {
            $card = $this->billing->card($user);

            if ($command->card) {
                $card->create($command->card);
                return User::card($user, $card);
            }
        } catch (BillingException $e) {
            throw new AppException($e->getMessage());
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
     * @param  ConfirmLessonBookingCommand $command
     * @return void
     */
    protected function guardAgainstInvalidData(ConfirmLessonBookingCommand $command)
    {
        // return $this->validator->validate((array) $command);
    }

    /**
     * Guard against a user not having permission to confirm a booking.
     *
     * @param  LessonBooking $booking
     * @param  User          $user
     * @return void
     */
    protected function guardAgainstUserNotPermitted(LessonBooking $booking, User $user)
    {
        if ($booking->lesson->relationship->student->id !== $user->id) {
            
            if (!$user->isAdmin()) {
                throw new UnauthorizedException();
            }
        }
    }

}

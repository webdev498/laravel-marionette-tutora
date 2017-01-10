<?php namespace App\Handlers\Commands;

use App\Lesson;
use App\LessonBooking;
use Illuminate\Database\DatabaseManager;
use App\Billing\Contracts\BillingInterface;
use App\Commands\PayForLessonBookingCommand;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Validators\BookWithCardLessonBookingValidator;

class PayForLessonBookingCommandHandler extends CommandHandler
{

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var LessonRepositoryInterface
     */
    protected $lessons;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * @var BillingInterface
     */
    protected $billing;

    /**
     * Create the command handler.
     *
     * @param  DatabaseManager                  $database
     * @param  LessonRepositoryInterface        $lessons
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  BillingInterface                 $billing
     * @return void
     */
    public function __construct(
        DatabaseManager                    $database,
        BookWithCardLessonBookingValidator $validator,
        LessonRepositoryInterface          $lessons,
        LessonBookingRepositoryInterface   $bookings,
        BillingInterface                   $billing
    ) {
        $this->database  = $database;
        $this->validator = $validator;
        $this->lessons   = $lessons;
        $this->bookings  = $bookings;
        $this->billing   = $billing;
    }

    /**
     * Handle the command.
     *
     * @param  PayForLessonBookingCommand  $command
     * @return void
     */
    public function handle(PayForLessonBookingCommand $command)
    {
        return $this->database->transaction(function () use ($command) {
            // Validate
            $this->guardAgainstInvalidData($command);

            // Normalise
            $id       = (integer) $command->id;
            $token    = (string)  $command->token;
            $user     = $command->user;

            // Lookups
            $booking = $this->bookings->findById($id);

            // Create a billing account, if the user doesn't
            // already have one
            if ( ! $this->billing->userHasAccount($user)) {
                $user = $this->billing->createAccountForUser($user);
            }

            // Update the users card
            $this->billing->saveCardForUser($user, $token);

            // Update the bookings
            $bookings = $this->bookings->getSiblingsUpdatedAtTheSameTimeByBooking($booking);
            foreach ($bookings as &$_booking) {
                LessonBooking::book($_booking);
                $this->bookings->save($_booking);
            }

            $booking->lesson->status       = Lesson::CONFIRMED;
            $booking->lesson->is_confirmed = true;
            $this->lessons->save($booking->lesson);

            // Essentially, reload the model
            $booking = $this->bookings->findById($id);

            $this->dispatchFor($booking);

            return $booking;
        });
    }

    /**
     * Guard against invalid data on the command.
     *
     * @param  PayForLessonBookingCommand $command
     * @return void
     */
    protected function guardAgainstInvalidData(PayForLessonBookingCommand $command)
    {
        return $this->validator->validate((array) $command);
    }

}

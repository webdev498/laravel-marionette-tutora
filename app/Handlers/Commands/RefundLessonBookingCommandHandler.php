<?php

namespace App\Handlers\Commands;

use App\Billing\Contracts\BillingInterface;
use App\Commands\RefundLessonBookingCommand;
use App\LessonBooking;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\User;
use App\Validators\RefundLessonBookingValidator;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager;
use Illuminate\Queue\InteractsWithQueue;

class RefundLessonBookingCommandHandler extends CommandHandler
{
    
    /**
     * @var BillingInterface
     */
    protected $billing;

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
     * @var ManagedAccountInterface
     */
    private $account;

    /**
     * Create the command handler.
     *
     * @param  DatabaseManager                   $database
     * @param  Auth                              $auth
     * @param  BillingInterface                   $billing 
     * @param  RefundLessonBookingValidator      $validator
     * @param  LessonBookingRepositoryInterface  $bookings
     * @return void
     */
    public function __construct(
        DatabaseManager                     $database,
        Auth                                $auth,
        BillingInterface                    $billing, 
        LessonBookingRepositoryInterface    $bookings,
        RefundLessonBookingValidator        $validator
    ) {  
        $this->database = $database;
        $this->auth = $auth;
        $this->billing = $billing;
        $this->bookings = $bookings;
        $this->validator = $validator;
    }

    /**
     * Handle the command.
     *
     * @param    $command
     * @return void
     */
    public function handle($command)
    {
        return $this->database->transaction(function () use ($command) {

            // Validate
            $this->guardAgainstInvalidData($command);

            // Lookups
            $user    = $this->auth->user();
            $booking = $this->findBooking($command->uuid);

            // Guard
            $this->guardAgainstUserNotPermitted($booking, $user);
            
            // Update connect account to pull funds from bank account.
            $this->updateDebitNegativeBalances($booking);
            
            // Refund
            $refund = $this->billing->refund($booking);
            $refund->now($command->reverse_transfer, $command->amount);

            if ($command->amount) 
            {
                $booking = LessonBooking::refundedPartially($booking);
            }

            if (! $command->amount)
            {
                $booking = LessonBooking::refunded($booking);
            }

            $this->bookings->save($booking);
            
            // Dispatch
            $this->dispatchFor($booking);

            return $booking;

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
     * Guard against invalid data on the command.
     *
     * @param  CancelLessonBookingCommand $command
     * @return void
     */
    protected function guardAgainstInvalidData(RefundLessonBookingCommand $command)
    {
        return $this->validator->validate((array) $command);
    }

    /**
     * Guard against a user not having permission to refund a booking.
     *
     * @param  LessonBooking $booking
     * @param  User          $user
     * @return void
     */
    protected function guardAgainstUserNotPermitted(LessonBooking $booking, User $user)
    {
        if (! $user->isAdmin()) {
            throw new UnauthorizedException();
        }
        
    }

    protected function updateDebitNegativeBalances($booking) 
    {

        $lesson   = $booking->lesson;
        $relationship = $lesson->relationship;
        $tutor = $relationship->tutor;
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $account = \Stripe\Account::retrieve($tutor->billing_id);

        $account->debit_negative_balances = true;
        $account->save();
    }
}

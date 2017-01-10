<?php

namespace App\Console\Commands;

use App\Tutor;
use Carbon\Carbon;
use App\LessonBooking;
use App\Billing\FeeCalculator;
use Illuminate\Database\DatabaseManager;
use App\Billing\Contracts\ChargeInterface;
use App\Billing\Contracts\BillingInterface;
use App\Billing\Contracts\Exceptions\BillingExceptionInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class AuthoriseLessonBookingPaymentsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:authorise_lesson_booking_payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Authorise payments on lesson bookings.';

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * @var BillingInterface
     */
    protected $billing;

    /**
     * @var FeeCalculator
     */
    protected $fee;

    /**
     * Create a new command instance.
     *
     * @param  DatabaseManager                  $database
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  BillingInterface                 $billing
     * @param  FeeCalculator                    $fee
     * @return void
     */
    public function __construct(
        DatabaseManager                  $database,
        LessonBookingRepositoryInterface $bookings,
        BillingInterface                 $billing,
        FeeCalculator                    $fee
    ) {
        parent::__construct();

        $this->database = $database;
        $this->bookings = $bookings;
        $this->billing  = $billing;
        $this->fee      = $fee;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        loginfo("[ Background ] {$this->name}");

        
        $date     = $this->getDate();
        $now      = Carbon::now();
        $bookings = $this->bookings->getAuthorisableBeforeDate($date);
        // Loopy
        
        foreach ($bookings as $booking) {
            
            $this->database->transaction(function () use ($booking, $now) {

                // Lookups
                
                $lesson       = $booking->lesson;
                $relationship = $lesson->relationship;
                $student      = $relationship->student;
                $tutor        = $relationship->tutor;
                // Bail
                if ( $student->last_four) {
                    // Billing
                    try {
                        // Accounts
                        $customer = $this->billing->account($student);
                        $payee    = $this->billing->account($tutor);
                        // Tutora fee
                        $fee = $this->fee->calculateByTutor($tutor);
                        // Authorise
                        $charge = $this->billing->charge($customer, $booking);
                        $charge->payee($payee);
                        $charge->fee($fee);
                        $charge->authorise();
                        // Vent
                        $booking = LessonBooking::authorised($booking, $charge);
                    } catch (BillingExceptionInterface $e) {
                        $booking = LessonBooking::authorisationFailed($booking, $now, $e);
                    }
                    // Dispatch
                    $this->dispatchFor($booking);
                    // Save
                    $this->bookings->save($booking);
                }

            
            });
        }
        
    }

    /**
     * Get the date to grab lessons by
     *
     * @return Carbon
     */
    protected function getDate()
    {
        $minutes = config('lessons.authorise_payment_period.to', 1440);

        return Carbon::now()->addMinutes($minutes);
    }


    protected function calculateFee(Tutor $tutor)
    {
        $calc = app('App\Billing\FeeCalculator');

        return $calc->calculateByTutor($tutor);
    }

}

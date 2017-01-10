<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\LessonBooking;
use Illuminate\Database\DatabaseManager;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class RetryLessonBookingPaymentsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:retry_lesson_booking_payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry payments on lesson bookings.';

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
     * Create a new command instance.
     *
     * @param  DatabaseManager                  $database
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  BillingInterface                 $billing
     * @return void
     */
    public function __construct(
        DatabaseManager                  $database,
        LessonBookingRepositoryInterface $bookings
    ) {
        parent::__construct();

        $this->database = $database;
        $this->bookings = $bookings;
        
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        loginfo("[ Background ] {$this->name}");

        $this->database->transaction(function () {
            
            // Lookup
            $bookings = $this->bookings->getByChargeStatus([
                LessonBooking::PAYMENT_FAILED, 
                LessonBooking::AUTHORISATION_FAILED
            ]);
            
            // Loopy
            foreach ($bookings as $booking) {
                              
                $student = $booking->student;
                $settings = $student->settings;

                $timeToRetry = $this->isTimeToRetry($booking);

                if ($timeToRetry && $settings->retry_failed_payments) {
                
                    // Reauthorise
                    $booking = LessonBooking::reauthorise($booking);
                    // Dispatch
                    $this->dispatchFor($booking);
                    // Save
                    $this->bookings->save($booking);
                }
                
            }
        });
    }

    /**
     * Do we need to retry the payment
     *
     * @return Boolean
     */
    protected function isTimeToRetry($booking)
    {
        $attempts = $booking->payment_attempts;
        $time_since_lesson  = $booking->start_at->diffInMinutes(Carbon::now(), false);

        $attempt_periods = config('lessons.retry_payment_periods');

        if ($attempts < config('lessons.max_retry_payment_attempts') && $time_since_lesson > $attempt_periods[$attempts])
        {
            return true;
        }    

        return false;
    }



}

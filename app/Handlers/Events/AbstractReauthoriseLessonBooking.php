<?php

namespace App\Handlers\Events;

use App\Student;
use App\LessonBooking;
use App\Events\LessonBookingWasEdited;
use App\Billing\Contracts\BillingInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

abstract class AbstractReauthoriseLessonBooking extends EventHandler
{

    /**
     * @var BillingInterface
     */
    protected $billing;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * Create an instance of this
     *
     * @param  BillingInterface
     * @param  LessonBookingRepositoryInterface $bookings
     * @return void
     */
    public function __construct(
        BillingInterface                 $billing,
        LessonBookingRepositoryInterface $bookings
    ) {
        $this->billing  = $billing;
        $this->bookings = $bookings;
    }

    protected function reauthorise(LessonBooking $booking, Student $student)
    {
        if ($booking->charge_status === LessonBooking::AUTHORISED) {
            // Refund
            $this->refund($booking, $student);
            // Reauthorise
            $booking = LessonBooking::reauthorise($booking);
            // Save
            $this->bookings->save($booking);
        }
    }

    protected function refund(LessonBooking $booking, Student $student)
    {
        // Charge
        $charge = $this->findCharge($student, $booking);
        // Refund
        if ($charge) {
            $charge->refund();
        }
    }

    /**
     * Find the charge for a given student & booking
     *
     * @param  Student $student
     * @param  LessonBooking $booking
     * @return StripeCharge|null
     */
    protected function findCharge(Student $student, LessonBooking $booking)
    {
        if ($booking->getChargeId()) {
            $customer = $this->billing->account($student);
            return $this->billing->charge($customer, $booking);
        }
    }

}


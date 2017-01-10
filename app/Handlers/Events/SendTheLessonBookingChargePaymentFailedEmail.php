<?php namespace App\Handlers\Events;

use App\Mailers\BillingMailer;
use App\Events\LessonBookingChargePaymentFailed;

class SendTheLessonBookingChargePaymentFailedEmail extends EventHandler
{

    /**
     * @var BillingMailer
     */
    protected $mailer;

    /**
     * Create the event handler.
     *
     * @param  BillingMailer $mailer
     * @return void
     */
    public function __construct(BillingMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingChargePaymentFailed $event
     * @return void
     */
    public function handle(LessonBookingChargePaymentFailed $event)
    {
        $booking      = $event->booking;
        $lesson       = $booking->lesson;
        $relationship = $lesson->relationship;
        $student      = $relationship->student;

        $this->mailer->chargePaymentFailedToStudent($student);
    }

}

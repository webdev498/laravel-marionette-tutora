<?php

namespace App\Handlers\Events;

use App\Events;
use App\Mailers\BillingMailer;
use App\Twilio\BillingTwilio;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTheLessonBookingChargeFailedNotifications extends EventHandler
{
    private $mailer;
    private $twilio;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(BillingMailer $mailer, BillingTwilio $twilio)
    {
        $this->mailer = $mailer;
        $this->twilio = $twilio;
    }

    /**
     * Handle the event.
     *
     * @param  Events  $event
     * @return void
     */
    public function handle($event)
    {
        //Lookups
        $booking      = $event->booking;
        $lesson       = $booking->lesson;
        $relationship = $lesson->relationship;
        $student      = $relationship->student;
        $studentSettings = $student->settings;
        $tutor        = $relationship->tutor;
        $attempts     = $booking->payment_attempts;

        // Notifications
        
        if ($studentSettings->send_failed_payment_notifications)
        {
            switch ($attempts) {

                case 1:
                    $this->mailer->firstChargeAttemptFailedToStudent($student, $tutor, $relationship, $booking);
                    $this->twilio->firstChargeAttemptFailedToStudent($student, $tutor, $relationship, $booking);
                    break;

                case 2:
                    $this->mailer->secondChargeAttemptFailedToStudent($student, $tutor, $relationship, $booking);
                    $this->twilio->secondChargeAttemptFailedToStudent($student, $tutor, $relationship, $booking);
                    break;

                case 3: 
                    $this->mailer->thirdChargeAttemptFailedToStudent($student, $tutor, $relationship, $booking);
                    $this->twilio->thirdChargeAttemptFailedToStudent($student, $tutor, $relationship, $booking);
                    break;

                default:
                    $this->mailer->forthChargeAttemptFailedToStudent($student, $tutor, $relationship, $booking);
                    $this->twilio->forthChargeAttemptFailedToStudent($student, $tutor, $relationship, $booking);

            }
        }

    }
}

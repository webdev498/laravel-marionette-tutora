<?php namespace App\Mailers;

use App\Lesson;
use App\LessonBooking;
use App\Tutor;

class TutorAccountMailer extends AbstractMailer
{

    /**
     * Tell the tutor that we have not been able to transfer their funds
     *
     * @param  Tutor   $tutor
     * @return void
     */
    public function transferFailed(
        Tutor $tutor
    ) { 

        $subject = "We couldn't transfer the funds to your bank account | Tutora";
        $view    = 'emails.tutor-account.transfer-failed';
        $data    = [
            'tutor' => $tutor
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }
}

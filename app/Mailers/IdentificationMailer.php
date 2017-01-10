<?php namespace App\Mailers;

use App\Tutor;

class IdentificationMailer extends AbstractMailer
{

    /**
     * Send an email to the tutor when their identification failed verification
     *
     * @param  Tutor  $tutor
     * @param  string $error
     * @return void
     */
    public function failedVerification(Tutor $tutor, $error)
    {
        $subject = 'There was a problem with your identification | Tutora';
        $view    = 'emails.identification.failed-verification';
        $data    = [
            'user'  => $tutor,
            'error' => $error,
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }

    /**
     * Send an email to the tutor when their idenficaition passes verification.
     *
     * @param  Tutor $tutor
     * @return void
     */
    public function passedVerification(Tutor $tutor)
    {
        $subject = 'Your identification has been verified! | Tutora';
        $view    = 'emails.identification.passed-verification';
        $data    = [
            'user' => $tutor,
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }

}

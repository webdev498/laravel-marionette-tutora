<?php namespace App\Mailers;

class ContactUsMailer extends AbstractMailer
{

    /**
     * Send a "contact us" email to tutora.
     *
     * @param  string $name  The senders name
     * @param  string $email The senders email
     * @param  string $subject The senders subject
     * @param  string $body  The senders message
     * @return void
     */
    public function sendEmail($name, $from, $subject, $body)
    {
        $to      = 'support@tutora.co.uk';
        $view    = 'emails.admin.contact';
        $data    = [
            'body'  => $body,
        ];

        $this->sendToFrom($to, $from, $subject, $view, $data);
    }

}

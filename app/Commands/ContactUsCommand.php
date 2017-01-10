<?php namespace App\Commands;

use App\Commands\Command;

class ContactUsCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $name,
        $email,
        $subject,
        $body
    ) {
        $this->name  = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->body  = $body;
    }

}

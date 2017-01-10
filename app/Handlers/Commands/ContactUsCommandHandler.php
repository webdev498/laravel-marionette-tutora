<?php namespace App\Handlers\Commands;

use App\Mailers\ContactUsMailer;
use App\Commands\ContactUsCommand;

class ContactUsCommandHandler extends CommandHandler
{

    /**
     * @var ContactUsMailer
     */
    protected $mailer;

    /**
     * Create the command handler.
     *
     * @param  ContactUsMailer $mailer
     * @return void
     */
    public function __construct(ContactUsMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the command.
     *
     * @param  ContactUsCommand  $command
     * @return void
     */
    public function handle(ContactUsCommand $command)
    {
        $this->mailer->sendEmail(
            $command->name,
            $command->email,
            $command->subject,
            $command->body
        );
    }

}

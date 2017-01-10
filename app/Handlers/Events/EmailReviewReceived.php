<?php namespace App\Handlers\Events;

use App\Mailers\UserMailer;
use App\Events\UserReviewWasLeft;

class EmailReviewReceived extends EventHandler
{

    /**
     * @var UserMailer
     */
    protected $mailer;

    /**
     * Create the event handler.
     *
     * @param  UserMailer $mailer
     * @return void
     */
    public function __construct(UserMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  UserReviewWasLeft $event
     * @return void
     */
    public function handle(UserReviewWasLeft $event)
    {
        $review  = $event->review;
        $user    = $review->user;

        $this->mailer->reviewed($user, $review);
    }

}

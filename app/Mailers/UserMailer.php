<?php namespace App\Mailers;

use App\User;
use App\Tutor;
use App\UserReview;
use App\MessageLine;
use App\LessonBooking;
use App\Presenters\TutorPresenter;
use App\Messaging\Parser as MessageLineParser;
use App\Billing\Contracts\Exceptions\BillingExceptionInterface;

class UserMailer extends AbstractMailer
{

    public function sendConfirmRegistrationEmail(User $user)
    {
        $subject = 'Welcome to Tutora!';
        $view    = $user instanceof Tutor
            ? 'emails.tutor.confirm-registration'
            : 'emails.student.confirm-registration';
        $data    = [
            'user'  => $user,
        ];

        $this->sendToUser($user, $subject, $view, $data);
    }

    public function sendMessageWasOpenedEmail(User $user, MessageLine $line)
    {
        $subject = "You've received a new message! | Tutora";
        $view    = 'emails.message.opened';
        $data    = [
            'line' => MessageLineParser::make($line),
            'user' => $user,
        ];

        $this->sendToUser($user, $subject, $view, $data);
    }

    public function sendMessageLineWasWrittenEmail(User $user, MessageLine $line)
    {
        if ($line->user) {
            $subject = "You've received a new message from " . $line->user->first_name;
        } else {
            $subject = "You've received a new message from Tutora";
        }

        $view    = 'emails.message.reply';
        $data    = [
            'line' => MessageLineParser::make($line),
            'user' => $user,
        ];
        
        $this->sendToUser($user, $subject, $view, $data);
    }

    /**
     * @param User $user
     * @param MessageLine $line
     */
    public function sendApplicationMessageLineWasWrittenEmail(User $user, MessageLine $line)
    {
        if ($line->user) {
            $subject = $line->user->first_name . ' can help with your tuition request';
        } else {
            $subject = "You've received a new message from Tutora";
        }

        $view    = 'emails.message.application';

        $line  = MessageLineParser::make($line);
        $tutor = $line->getLine()->user;
        $data    = [
            'line' => $line,
            'tutor' => $this->presentItem($tutor, new TutorPresenter(), [
                'include'   => [
                    'profile'
                ]
            ]),
            'user' => $user,
        ];

        $this->sendToUser($user, $subject, $view, $data);
    }

    /**
     * Send an email to the user notifiying them that they have been reviewed
     *
     * @param  User $user
     * @return void
     */
    public function review(User $reviewer, User $reviewee)
    {
        $subject = "Please leave a review for your lesson with {$reviewee->first_name} | Tutora";
        $view    = 'emails.users.review';
        $data    = [
            'reviewer' => $reviewer,
            'reviewee' => $reviewee
        ];

        $this->sendToUser($reviewer, $subject, $view, $data);
    }

    /**
     * Send an email to the user notifiying them that they have been reviewed
     *
     * @param  User $user
     * @return void
     */
    public function reviewed(User $user, UserReview $review)
    {
        $subject = "{$review->reviewer->first_name} has left you a review!| Tutora";
        $view    = 'emails.users.reviewed';
        $data    = [
            'review' => $review
        ];

        $this->sendToUser($user, $subject, $view, $data);
    }


}

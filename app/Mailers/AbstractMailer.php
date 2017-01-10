<?php namespace App\Mailers;

use App\User;
use App\Presenters\PresenterTrait;
use Illuminate\Mail\Mailer as Mail;

abstract class AbstractMailer
{

    use PresenterTrait;

    /**
     * @var Mail
     */
    protected $mailer;

    /**
     * Create an instance of the mailer
     *
     * @param  Mail $mail
     * @return void
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Send an email to a given email address
     *
     * @param  string $email
     * @param  string $subject
     * @param  string $view
     * @param  array  $data
     * @return void
     */
    public function sendTo($email, $subject, $view, array $data)
    {
        $this->mail->send($view, $data, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }

    /**
     * Send an email to a given email address from an email address
     *
     * @param  string $email
     * @param  string $from
     * @param  string $subject
     * @param  string $view
     * @param  array  $data
     * @return void
     */
    public function sendToFrom($email, $from, $subject, $view, array $data)
    {
        $this->mail->send($view, $data, function ($message) use ($email, $subject, $from) {
            $message->to($email)->subject($subject);
            $message->from($from);
        });
    }

    /**
     * Send an email to the given user
     *
     * @param  User   $user
     * @param  string $subject
     * @param  string $view
     * @param  array  $data
     * @return void
     */
    public function sendToUser(User $user, $subject, $view, array $data, $list = null)
    {
        if ($user->deleted_at) {
            return;
        }

        if ($user->blocked_at) {
            return;
        }

        if ($list !== null && ! $user->subscription->isSubscribed($list)) {
            return;
        }

        $data['list'] = $list;

        if (! $user) return; 
        $this->mail->send($view, $data, function ($message) use ($user, $subject) {
            $message->to($user->email, "{$user->first_name} {$user->last_name}")->subject($subject);
        });
    }

    public function sendToSystem(User $user, $subject, $view, array $data)
    {
        if ($user->deleted_at) {
            return;
        }

        if ($user->blocked_at) {
            return;
        }

        $this->mail->send($view, $data, function ($message) use ($user, $subject) {
            $message->to(config('mail.from.address'), "{$user->first_name} {$user->last_name}")->subject($subject);
        });
    }

}

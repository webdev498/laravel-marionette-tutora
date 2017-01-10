<?php namespace App\Mailers;

use App\User;

class PasswordMailer extends AbstractMailer
{

    public function sendRequestPasswordResetMessageTo(User $user, $token)
    {
        $subject = 'Password Reset';
        $view    = 'emails.password.request';
        $data    = [
            'user'  => $user,
            'token' => $token,
        ];

        $this->sendToUser($user, $subject, $view, $data);
    }

}

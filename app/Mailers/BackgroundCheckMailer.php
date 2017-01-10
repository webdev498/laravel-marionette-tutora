<?php namespace App\Mailers;

use App\UserBackgroundCheck;

class BackgroundCheckMailer extends AbstractMailer
{

    const TEMPLATE_EXPIRING_TWO_WEEKS_AFTER = 'emails.background-check.expiring-after-two-weeks';
    const TEMPLATE_EXPIRING_ON              = 'emails.background-check.expiring-on';
    const TEMPLATE_EXPIRING_WEEK_BEFORE     = 'emails.background-check.expiring-before-week';

    const TEMPLATE_ADMIN_STATUS_APPROVED    = 'emails.background-check.approved-background';
    const TEMPLATE_ADMIN_STATUS_REJECTED    = 'emails.background-check.rejected-background';

    /**
     * Send an email to the tutor when their background check admin status approved
     *
     * @param  UserBackgroundCheck $background
     *
     * @return void
     */
    public function adminStatusApproved(UserBackgroundCheck $background)
    {
        $tutor = $background->user;

        $subject = 'Your background check was approved! | Tutora';
        $view    = self::TEMPLATE_ADMIN_STATUS_APPROVED;
        $data    = [
            'user'       => $tutor,
            'background' => $background,
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }

    /**
     * Send an email to the tutor when their background check admin status rejected
     *
     * @param  UserBackgroundCheck $background
     *
     * @return void
     */
    public function adminStatusRejected(UserBackgroundCheck $background)
    {
        $tutor = $background->user;

        $subject = 'Your background check was rejected | Tutora';
        $view    = self::TEMPLATE_ADMIN_STATUS_REJECTED;
        $data    = [
            'user'       => $tutor,
            'background' => $background,
        ];
    
        $this->sendToUser($tutor, $subject, $view, $data);
    }

    /**
     * Expiring notification
     *
     * @param string $template
     * @param UserBackgroundCheck $background
     */
    public function expiringNotification($template, UserBackgroundCheck $background)
    {
        $tutor = $background->user;

        switch ($template) {
            case self::TEMPLATE_EXPIRING_WEEK_BEFORE:
                $subject = 'Your DBS Check will expire soon | Tutora';
                break;
            
            case self::TEMPLATE_EXPIRING_ON:
                $subject = 'Your DBS Check has expired | Tutora';
                break;

            case self::TEMPLATE_EXPIRING_TWO_WEEKS_AFTER:
                $subject = 'Your DBS Check expired 2 weeks ago | Tutora';
                break;

            default:
                $subject = "Your background check needs updating";
                break;
        }

        $data    = [
            'user'       => $tutor,
            'background' => $background,
        ];

        $this->sendToUser($tutor, $subject, $template, $data);
    }
}

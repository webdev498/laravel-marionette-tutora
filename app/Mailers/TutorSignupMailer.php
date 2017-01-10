<?php namespace App\Mailers;

use App\Tutor;
use App\UserSubscription;
use App\UserRequirementCollection;
use App\Presenters\TutorPresenter;
use App\Presenters\Email\UserRequirementsEmailPresenter;

class TutorSignupMailer extends AbstractMailer
{
    /**
     * Send first Reminder email to tutor
     *
     * @param  Tutor         $tutor
     * @param  Requirements  $requirements
     * @return void
     */
    public function firstSignupReminder(
        Tutor         $tutor,
        UserRequirementCollection $requirements
    ) { 

        $subject = "Thanks for signing up...but we still need a bit more information | Tutora";
        $view    = 'emails.tutor-reminder.first-signup-reminder';
        $list    = UserSubscription::SIGNUP;
        $data    = [
            'tutor' => $this->presentItem($tutor, new TutorPresenter(), [
            'include' => [
                'private',
                'admin',
            ]
        ]),
            'requirements' => $this->presentItem($requirements, new UserRequirementsEmailPresenter())
        ];

        return $this->sendToUser($tutor, $subject, $view, $data, $list);
    }

    /**
     * Send second Reminder email to tutor
     *
     * @param  Tutor         $tutor
     * @param  Requirements  $requirements
     * @return void
     */
    public function secondSignupReminder(
        Tutor         $tutor,
        UserRequirementCollection $requirements
    ) { 

        $subject = "Please complete your profile with us to start Tutoring | Tutora";
        $view    = 'emails.tutor-reminder.second-signup-reminder';
        $list    = UserSubscription::SIGNUP;
        $data    = [
            'tutor' => $this->presentItem($tutor, new TutorPresenter(), [
            'include' => [
                'private',
                'admin',
            ]
        ]),
            'requirements' => $this->presentItem($requirements, new UserRequirementsEmailPresenter())
        ];

        return $this->sendToUser($tutor, $subject, $view, $data, $list);
    }

       /**
     * Send first Reminder email to tutor
     *
     * @param  Tutor         $tutor
     * @param  Requirements  $requirements
     * @return void
     */
    public function thirdSignupReminder(
        Tutor         $tutor,
        UserRequirementCollection $requirements
    ) { 

        $subject = "Reminder: You have not completed your profile | Tutora";
        $view    = 'emails.tutor-reminder.third-signup-reminder';
        $list    = UserSubscription::SIGNUP;
        $data    = [
            'tutor' => $this->presentItem($tutor, new TutorPresenter(), [
            'include' => [
                'private',
                'admin',
            ]
        ]),
            'requirements' => $this->presentItem($requirements, new UserRequirementsEmailPresenter())
        ];

        return $this->sendToUser($tutor, $subject, $view, $data, $list);
    }

   /**
     * Send final Reminder email to tutor
     *
     * @param  Tutor         $tutor
     * @param  Requirements  $requirements
     * @return void
     */
    public function finalSignupReminder(
        Tutor         $tutor,
        UserRequirementCollection $requirements
    ) { 

        $subject = "Final chance to complete your profile | Tutora";
        $view    = 'emails.tutor-reminder.final-signup-reminder';
        $list    = UserSubscription::SIGNUP;
        $data    = [
            'tutor' => $this->presentItem($tutor, new TutorPresenter(), [
            'include' => [
                'private',
                'admin',
            ]
        ]),
            'requirements' => $this->presentItem($requirements, new UserRequirementsEmailPresenter())
        ];

        return $this->sendToUser($tutor, $subject, $view, $data, $list);
    }

   /**
     * Send final Reminder email to tutor
     *
     * @param  Tutor         $tutor
     * @param  Requirements  $requirements
     * @return void
     */
    public function firstIdentificationSignupReminder(
        Tutor         $tutor,
        UserRequirementCollection $requirements
    ) { 

        $subject = "Thanks for signing up...but we still need your ID | Tutora";
        $view    = 'emails.tutor-reminder.first-identification-reminder';
        $list    = UserSubscription::SIGNUP;
        $data    = [
            'tutor' => $this->presentItem($tutor, new TutorPresenter(), [
            'include' => [
                'private',
                'admin',
            ]
        ]),
            'requirements' => $this->presentItem($requirements, new UserRequirementsEmailPresenter())
        ];

        return $this->sendToUser($tutor, $subject, $view, $data, $list);
    }

   /**
     * Send final Reminder email to tutor
     *
     * @param  Tutor         $tutor
     * @param  Requirements  $requirements
     * @return void
     */
    public function secondIdentificationSignupReminder(
        Tutor         $tutor,
        UserRequirementCollection $requirements
    ) { 

        $subject = "Almost there...please upload a copy of your ID | Tutora";
        $view    = 'emails.tutor-reminder.second-identification-reminder';
        $list    = UserSubscription::SIGNUP;
        $data    = [
            'tutor' => $this->presentItem($tutor, new TutorPresenter(), [
            'include' => [
                'private',
                'admin',
            ]
        ]),
            'requirements' => $this->presentItem($requirements, new UserRequirementsEmailPresenter())
        ];

        return $this->sendToUser($tutor, $subject, $view, $data, $list);
    }

   /**
     * Send final Reminder email to tutor
     *
     * @param  Tutor         $tutor
     * @param  Requirements  $requirements
     * @return void
     */
    public function thirdIdentificationSignupReminder(
        Tutor         $tutor,
        UserRequirementCollection $requirements
    ) { 

        $subject = "Reminder: Please send us a copy of your ID to start tutoring | Tutora";
        $view    = 'emails.tutor-reminder.third-identification-reminder';
        $list    = UserSubscription::SIGNUP;
        $data    = [
            'tutor' => $this->presentItem($tutor, new TutorPresenter(), [
            'include' => [
                'private',
                'admin',
            ]
        ]),
            'requirements' => $this->presentItem($requirements, new UserRequirementsEmailPresenter())
        ];

        return $this->sendToUser($tutor, $subject, $view, $data, $list);
    }

}
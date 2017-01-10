<?php namespace App\Mailers;

use App\Lesson;
use App\LessonBooking;
use App\MessageLine;
use App\Presenters\LessonBookingPresenter;
use App\Presenters\LessonPresenter;
use App\Presenters\RelationshipPresenter;
use App\Relationship;
use App\Messaging\Parser as MessageLineParser;
use App\Student;
use App\Tutor;

class TutorMailer extends AbstractMailer
{




    /**
     * Send an email to the tutor regarding a lesson being cancelled.
     *
     * @param  Tutor         $tutor
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @return void
     */
    public function noReplyToMessageLineFirstReminder(
        MessageLine   $line,
        Tutor         $tutor,
        Student       $student,  
        Relationship  $relationship
    ) { 
        $relationship = $this->presentItem($relationship, new RelationshipPresenter(), [
            'include' => [
                'tutor',
                'student',
            ],
        ]);

        $subject = "Reminder: You have not replied to your enquiry from {$student->first_name} | Tutora";
        $view    = 'emails.tutor.no-reply-first-reminder';
        $data    = [
            'relationship' => $relationship,
            'line' => MessageLineParser::make($line),
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }

    /**
     * Send a reminder to reply email to the tutor .
     *
     * @param  MessageLine   $line
     * @param  Tutor         $tutor
     * @param  Student       $studnet
     * @param  Relationship  $relationship
     * @return void
     */
    public function noReplyToMessageLineSecondReminder(
        MessageLine   $line,
        Tutor         $tutor,
        Student       $student,  
        Relationship  $relationship
    ) { 
        $relationship = $this->presentItem($relationship, new RelationshipPresenter(), [
            'include' => [
                'tutor',
                'student',
            ],
        ]);

        $subject = "Reminder: You have not replied to your enquiry from {$student->first_name} | Tutora";
        $view    = 'emails.tutor.no-reply-second-reminder';
        $data    = [
            'relationship' => $relationship,
            'line' => MessageLineParser::make($line),
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }

   /**
     * Send an email to the tutor explaining that their profile has been taken offline
     *
     * @param  Tutor   $tutor
     * @return void
     */
    public function noResponseLimitReached(
        Tutor $tutor
    ) { 

        $subject = "We have taken your profile offline | Tutora";
        $view    = 'emails.tutor.no-response-limit-reached';
        $data    = [
            'tutor' => $tutor
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }

    /**
     * Send an email to the tutor asking them if they want to go back online
     *
     * @param  Tutor   $tutor
     * @return void
     */
    public function goOnlineReminder(
        Tutor $tutor
    ) { 

        $subject = "Are you looking for new students? | Tutora";
        $view    = 'emails.tutor.go-online';
        $data    = [
            'tutor' => $tutor
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }

    /**
     * Tell the tutor that we have not been able to transfer their funds
     *
     * @param  Tutor   $tutor
     * @return void
     */
    public function transferFailed(
        Tutor $tutor
    ) { 

        $subject = "We couldn't transfer the funds to your bank account | Tutora";
        $view    = 'emails.tutor-account.transfer-failed';
        $data    = [
            'tutor' => $tutor
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }


}

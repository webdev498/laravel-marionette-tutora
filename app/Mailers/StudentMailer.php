<?php namespace App\Mailers;

use App\MessageLine;
use App\Presenters\RelationshipPresenter;
use App\Relationship;
use App\Student;
use App\Tutor;
use App\Messaging\Parser as MessageLineParser;

class StudentMailer extends AbstractMailer
{

    /**
     * Send an email to the tutor regarding a lesson being cancelled.
     *
     * @param  MessageLine         $line
     * @param  Tutor  $tutor
     * @param  Student        $student
     * @param  Relationship $relationship
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

        $subject = "Hey $student->first_name, your tutor $tutor->first_name has said they can help! | Tutora";
        $view    = 'emails.student.no-reply-first-reminder';
        $data    = [
            'relationship' => $relationship,
            'line' => MessageLineParser::make($line),
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

}
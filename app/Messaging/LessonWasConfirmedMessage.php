<?php namespace App\Messaging;

use App\Lesson;
use App\LessonBooking;
use App\MessageLine;
use App\Messaging\Contracts\MessageInterface;
use App\Presenters\AddressesPresenter;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Student;
use App\Tutor;
use Illuminate\Auth\AuthManager as Auth;

class LessonWasConfirmedMessage extends AbstractMessage implements MessageInterface 
{
    
    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var LessonRepositoryInterface
     */
    protected $lessons;

    /**
     * Create an instance of the message
     *
     * @param  Auth
     * @param  LessonRepositoryInterface $lessons
     * @return void
     */
    public function __construct(
        Auth                      $auth,
        LessonRepositoryInterface $lessons
    ) {
        $this->auth    = $auth;
        $this->lessons = $lessons;
    }

    /**
     * Present the message line
     *
     * @param  MessageLine $line
     * @param  Array       $data
     * @return String
     */
    public function present(MessageLine $line, $data)
    {
        $user   = $this->auth->user();
        $lesson = $this->lessons->findById($data->lesson_id);


        if ($lesson === null) {
            $body = 'A lesson was confirmed, but, the record has since been removed.';
        } else {
            $subject      = $lesson->subject;
            $relationship = $lesson->relationship;
            $student      = $relationship->student;
            $addresses      =  $this->presentItem($student->addresses, new AddressesPresenter());


            $body = sprintf(
                '<strong>%s</strong> has confirmed a lesson in <strong>%s</strong>.',
                $student->first_name,
                $subject->title
            );

            if ($user instanceof Tutor) {
                $body .= 
                    ' Here is the student\'s address but make sure you\'re both in agreement as to where the lessons are going to take place: ' . $addresses->billing->string;
            }
        }

        return '<p>'.$body.'</p>';
    }

}

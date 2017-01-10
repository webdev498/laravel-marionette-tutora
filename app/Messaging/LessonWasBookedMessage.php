<?php namespace App\Messaging;

use App\Lesson;
use App\Student;
use App\Tutor;
use App\MessageLine;
use App\LessonBooking;
use Illuminate\Auth\AuthManager as Auth;
use App\Messaging\Contracts\MessageInterface;
use App\Repositories\Contracts\LessonRepositoryInterface;

class LessonWasBookedMessage extends AbstractMessage implements MessageInterface
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
            $body = 'A lesson was booked, but, the record has since been removed.';
        } else {

            if ($lesson->type == Lesson::REGULAR) {
                $body = sprintf(
                    '<strong>%s</strong> has booked a lesson in <strong>%s</strong>.',
                    $lesson->relationship->tutor->first_name,
                    $lesson->subject->title
                );

                if ($user instanceof Student && $lesson->status === Lesson::PENDING) {
                    $body .= sprintf(
                        ' Please confirm the lesson by <a href="%s">clicking here.</a> It will ask you to enter your payment details. We don\'t take any payment until 24 hours after the lesson, but the lesson must be confirmed 12 hours before it is due to start, else the lesson is automatically cancelled on the system and %s won\'t be able to teach the lesson. <strong>Sorry - our tutors cannot accept cash for any lessons.</strong> Thanks, Faye (Tutora)',
                        route('student.lessons.confirm', [
                            'booking' => $lesson->bookings->first()->uuid
                        ]), $lesson->relationship->tutor->first_name
                    );
                }
            } else {

                $body = sprintf(
                    '<strong>%s</strong> has booked a trial lesson at a cost of £%s.',
                    $lesson->relationship->tutor->first_name,
                    $lesson->bookings->first()->price
                );

                if ($user instanceof Student && $lesson->status === Lesson::PENDING) {
                    $body .= sprintf(
                        ' Please confirm the lesson by <a href="%s">clicking here.</a> It will ask you to enter your payment details. We don\'t take any payment until 24 hours after the lesson, but the lesson must be confirmed 12 hours before it is due to start, else the lesson is automatically cancelled on the system and %s won\'t be able to teach the lesson. <strong>Sorry - our tutors cannot accept cash for any lessons.</strong> Thanks, Faye (Tutora)',
                        route('student.lessons.confirm', [
                            'booking' => $lesson->bookings->first()->uuid
                        ]), $lesson->relationship->tutor->first_name
                    );
                }
            }

            if ($user instanceof Tutor && $lesson->status === Lesson::PENDING) {
                $body .= sprintf(
                    ' %s will be asked to enter their payment details to confirm the lesson. If it is not confirmed in time, it will be automatically cancelled on the system. Please do not attend if this is the case.<strong> Please do not accept cash for any lessons.</strong>', 
                    $lesson->relationship->student->first_name
                );
            }
        }

        return '<p>'.$body.'</p>';
    }

}

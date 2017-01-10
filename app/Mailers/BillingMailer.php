<?php namespace App\Mailers;

use App\Student;
use App\Tutor;
use App\Lesson;
use App\Relationship;
use App\LessonBooking;
use App\Presenters\LessonPresenter;
use App\Presenters\StudentPresenter;
use App\Presenters\TutorPresenter;
use App\Presenters\RelationshipPresenter;
use App\Presenters\LessonBookingPresenter;
use App\Billing\Contracts\Exceptions\BillingExceptionInterface;

class BillingMailer extends AbstractMailer
{

    /**
     * Send the first charge authorisation failed email to the student.
     *
     * @param  Student $student
     * @return void
     */
    public function firstChargeAttemptFailedToStudent(
        Student $student,
        Tutor         $tutor,
        Relationship  $relationship,
        LessonBooking $booking
    ) {
        $subject = "Reminder: please make sure there are funds available for your lesson with {$relationship->tutor->first_name} | Tutora";
        $view    = 'emails.billing.first-charge-attempt-failed-student';
        $data    = [
            'student'       => $this->presentItem($student, new StudentPresenter()),
            'tutor'         => $this->presentItem($tutor, new TutorPresenter()),
            'relationship'  => $this->presentItem($relationship, new RelationshipPresenter()),
            'booking'       => $this->presentItem($booking, new LessonBookingPresenter()),
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    /**
     * Send the second charge payment failed email to the student.
     *
     * @param  Student $student
     * @return void
     */
    public function secondChargeAttemptFailedToStudent(
        Student $student,
        Tutor         $tutor,
        Relationship  $relationship,
        LessonBooking $booking
    ) {
        $subject = "Payment failed for your lesson with {$relationship->tutor->first_name} | Tutora";
        $view    = 'emails.billing.second-charge-attempt-failed-student';
        $data    = [
            'student'       => $this->presentItem($student, new StudentPresenter()),
            'tutor'         => $this->presentItem($tutor, new TutorPresenter()),
            'relationship'  => $this->presentItem($relationship, new RelationshipPresenter()),
            'booking'       => $this->presentItem($booking, new LessonBookingPresenter()),
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    /**
     * Send the charge payment failed email to the student.
     *
     * @param  Student $student
     * @return void
     */
    public function thirdChargeAttemptFailedToStudent(
        Student $student,
        Tutor         $tutor,
        Relationship  $relationship,
        LessonBooking $booking
    ) {
        $subject = "Payment outstanding for your lesson with {$relationship->tutor->first_name} | Tutora";
        $view    = 'emails.billing.third-charge-attempt-failed-student';
        $data    = [
            'student' => $this->presentItem($student, new StudentPresenter()),
            'tutor'         => $this->presentItem($tutor, new TutorPresenter()),
            'relationship'  => $this->presentItem($relationship, new RelationshipPresenter()),
            'booking'       => $this->presentItem($booking, new LessonBookingPresenter()),
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    /**
     * Send the forth charge payment failed email to the student.
     *
     * @param  Student $student
     * @return void
     */
    public function forthChargeAttemptFailedToStudent(
        Student $student,
        Tutor         $tutor,
        Relationship  $relationship,
        LessonBooking $booking
    ) {
        $subject = "FINAL NOTICE: We have not been able to collect payment for your lesson on {$this->formatLessonDate($booking)} | Tutora";
        $view    = 'emails.billing.forth-charge-attempt-failed-student';
        $data    = [
            'student' => $this->presentItem($student, new StudentPresenter()),
            'tutor'         => $this->presentItem($tutor, new TutorPresenter()),
            'relationship'  => $this->presentItem($relationship, new RelationshipPresenter()),
            'booking'       => $this->presentItem($booking, new LessonBookingPresenter()),
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    /**
     * Send the charge was paid email to the student.
     *
     * @param  Student       $student
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @return void
     */
    public function chargePaymentPaidToStudent(
        Student       $student,
        Relationship  $relationship,
        Lesson        $lesson,
        LessonBooking $booking
    ) {
        $relationship = $this->presentItem($relationship, new RelationshipPresenter(), [
            'include' => [
                'tutor',
                'student',
            ],
        ]);
        $lesson  = $this->presentItem($lesson, new LessonPresenter(), [
            'include' => [
                'subject',
                'schedule',
            ],
        ]);
        $booking = $this->presentItem($booking, new LessonBookingPresenter());

        $subject = 'Payment received for lesson | Tutora';
        $view    = 'emails.billing.charge-paid';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
            'booking'      => $booking,
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    protected function formatLessonDate($booking)
    {
        return $booking->start_at->format('D jS M');
    }

}

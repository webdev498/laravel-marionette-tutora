<?php namespace App\Mailers;

use App\Tutor;
use App\Lesson;
use App\Relationship;
use App\LessonBooking;
use App\Presenters\LessonPresenter;
use App\Presenters\RelationshipPresenter;
use App\Presenters\LessonBookingPresenter;
use Illuminate\Database\Eloquent\Collection;

class TutorsLessonMailer extends AbstractMailer
{

    /**
     * Send an email to the tutor regarding a lesson being confirmed.
     *
     * @param  Tutor         $tutor
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @return void
     */
    public function lessonConfirmed(
        Tutor         $tutor,
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

        $subject = "Your lesson with {$relationship->student->first_name} has been confirmed | Tutora";
        $view    = 'emails.tutors-lesson.lesson-confirmed';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
            'booking'      => $booking,
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }

    /**
     * Send an email to the tutor regarding a lesson being cancelled.
     *
     * @param  Tutor         $tutor
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @param  Collection    $nextBookings
     *
     * @return void
     */
    public function lessonCancelled(
        Tutor         $tutor,
        Relationship  $relationship,
        Lesson        $lesson,
        LessonBooking $booking,
        Collection    $nextBookings
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

        $nextBookings = $this->presentCollection($nextBookings, new LessonBookingPresenter(), [
            'include' => [
                'lesson',
            ]
        ]);

        $subject = "Your lesson with {$relationship->student->first_name} has been cancelled | Tutora";
        $view    = 'emails.tutors-lesson.lesson-cancelled';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
            'booking'      => $booking,
            'nextBookings' => $nextBookings,
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }

    /**
     * Send an email to the tutor reminding them of an upcoming lesson
     */
    public function lessonUpcoming(
        Tutor         $tutor,
        Lesson        $lesson,
        LessonBooking $booking
    ) {
        $lesson  = $this->presentItem($lesson, new LessonPresenter());
        $booking = $this->presentItem($booking, new LessonBookingPresenter());

        $subject = "Reminder: Your lesson with {$booking->student->first_name} is tomorrow | Tutora";
        $view    = 'emails.tutors-lesson.lesson-upcoming';
        $data    = [
            'lesson'  => $lesson,
            'booking' => $booking,
        ];

        $this->sendToUser($tutor, $subject, $view, $data);
    }

}

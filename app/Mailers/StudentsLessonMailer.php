<?php namespace App\Mailers;

use App\Lesson;
use App\Student;
use App\Relationship;
use App\LessonBooking;
use App\Presenters\LessonPresenter;
use App\Presenters\RelationshipPresenter;
use App\Presenters\LessonBookingPresenter;
use Illuminate\Database\Eloquent\Collection;

class StudentsLessonMailer extends AbstractMailer
{

    /**
     * Send an email to the student regarding a lesson being booked.
     *
     * @param  Student       $student
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @return void
     */
    public function lessonBooked(
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

        $subject = "Please confirm your lesson with {$relationship->tutor->first_name} | Tutora";
        $view    = 'emails.students-lesson.lesson-booked';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
            'booking'      => $booking,
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    /**
     * Send an email to the student regarding a lesson being auto booked.
     *
     * @param  Student       $student
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @return void
     */
    public function lessonAutoBooked(
        Student       $student,
        Relationship  $relationship,
        Lesson        $lesson,
        LessonBooking $booking
    ) {
        $relationship = $this->presentItem($relationship, new RelationshipPresenter(), [
            'include' => [
                'tutor',
                'student',
            ]
        ]);
        $lesson  = $this->presentItem($lesson, new LessonPresenter(), [
            'include' => [
                'subject',
                'schedule',
            ],
        ]);
        $booking = $this->presentItem($booking, new LessonBookingPresenter());

        $subject = "{$relationship->tutor->first_name} has booked a lesson with you | Tutora";
        $view    = 'emails.students-lesson.lesson-auto-booked';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
            'booking'      => $booking,
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }


    /**
     * Send an email to the student regarding a lesson being edited.
     *
     * @param  Student       $student
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @return void
     */
    public function lessonEdited(
        Student       $student,
        Relationship  $relationship,
        Lesson        $lesson
    ) { 
        $relationship = $this->presentItem($relationship, new RelationshipPresenter(), [
            'include' => [
                'tutor',
                'student',
            ]
        ]);
        $lesson  = $this->presentItem($lesson, new LessonPresenter(), [
            'include' => [
                'subject',
                'schedule',
            ],
        ]);

        $subject = "{$relationship->tutor->first_name}  has changed the details of your lesson | Tutora";
        $view    = 'emails.students-lesson.lesson-edited';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    /**
     * Send an email to the student regarding a lesson being edited.
     *
     * @param  Student       $student
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @return void
     */
    public function lessonBookingEdited(
        Student       $student,
        Relationship  $relationship,
        Lesson        $lesson,
        LessonBooking $booking
    ) { 
        $relationship = $this->presentItem($relationship, new RelationshipPresenter(), [
            'include' => [
                'tutor',
                'student',
            ]
        ]);
        $lesson  = $this->presentItem($lesson, new LessonPresenter(), [
            'include' => [
                'subject',
                'schedule',
            ],
        ]);
        $booking = $this->presentItem($booking, new LessonBookingPresenter());

        $subject = "{$relationship->tutor->first_name} has changed the details of your lesson | Tutora";
        $view    = 'emails.students-lesson.lesson-booking-edited';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
            'booking'      => $booking,
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    /**
     * Send an email to the student regarding a lesson being confirmed.
     *
     * @param  Student       $student
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @return void
     */
    public function lessonConfirmed(
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

        $subject = "Your lesson with {$relationship->tutor->first_name} has been confirmed | Tutora";
        $view    = 'emails.students-lesson.lesson-confirmed';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
            'booking'      => $booking,
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    /**
     * Send an email to the student regarding a lesson being cancelled.
     *
     * @param  Student       $student
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @param  Collection    $nextBookings
     *
     * @return void
     */
    public function lessonCancelled(
        Student       $student,
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

        $subject = "Your lesson with {$relationship->tutor->first_name} has been cancelled | Tutora";
        $view    = 'emails.students-lesson.lesson-cancelled';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
            'booking'      => $booking,
            'nextBookings' => $nextBookings,
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    /**
     * Send an email to the student regarding a lesson being expired.
     *
     * @param  Student       $student
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @return void
     */
    public function lessonExpired(
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

        $subject = "Your lesson with {$relationship->tutor->first_name} has expired | Tutora";
        $view    = 'emails.students-lesson.lesson-expired';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
            'booking'      => $booking,
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    /**
     * Send an email to the student reminding them of an upcoming lesson
     *
     * @param  Student       $student
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @return void
     */
    public function lessonUpcoming(
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

        $subject = "Reminder: Your lesson with {$relationship->tutor->first_name} is tomorrow | Tutora";
        $view    = 'emails.students-lesson.lesson-upcoming';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
            'booking'      => $booking,
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

    /**
     * Send an email to the student reminding them of a lesson still pending confirmation
     *
     * @param  Student       $student
     * @param  Relationship  $relationship
     * @param  Lesson        $lesson
     * @param  LessonBooking $booking
     * @return void
     */
    public function lessonStillPending(
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

        $subject = "Reminder: Please confirm your lesson with {$relationship->tutor->first_name} | Tutora";
        $view    = 'emails.students-lesson.lesson-still-pending';
        $data    = [
            'relationship' => $relationship,
            'lesson'       => $lesson,
            'booking'      => $booking,
        ];

        $this->sendToUser($student, $subject, $view, $data);
    }

}

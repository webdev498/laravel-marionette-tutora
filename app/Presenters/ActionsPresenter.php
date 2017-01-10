<?php

namespace App\Presenters;

use App\Tutor;
use App\Student;
use App\LessonBooking;

class ActionsPresenter extends AbstractPresenter
{
    public $student = null;

    /**
     * Turn this object into a generic array
     *
     * @param  Tutor $tutor
     *
     * @return array
     */
    public function transform(Tutor $tutor)
    {
        $result = [
            'review' => $this->tutorCanBeReviewed($tutor),
        ];

        if($this->student) {
            $result['reviewByStudent'] = $this->tutorCanBeReviewedByStudent($tutor, $this->student);
        }

        return $result;
    }

    /**
     * Determine if a tutor can be reviewed.
     *
     * @param  Tutor $tutor
     * @return boolean
     */
    protected function tutorCanBeReviewed(Tutor $tutor)
    {
        if ($tutor->reviews->count() < 1 && count($tutor->relationships) === 1) {
            $relationship = clone($tutor->relationships[0]);
            $relationship->load([
                'lessons',
                'lessons.bookings'
            ]);

            foreach ($relationship->lessons as $lesson) {
                foreach ($lesson->bookings as $booking) {
                    if ($booking->status === LessonBooking::COMPLETED) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Determine if a tutor can be reviewed.
     *
     * @param  Tutor    $tutor
     * @param  Student  $student
     *
     * @return boolean
     */
    protected function tutorCanBeReviewedByStudent(Tutor $tutor, Student $student)
    {
        $relationship = $tutor->relationships()->where('student_id', '=', $student->id)->first();

        if(!$relationship) {return false;}

        $relationship->load([
            'lessons',
            'lessons.bookings'
        ]);

        foreach ($relationship->lessons as $lesson) {
            foreach ($lesson->bookings as $booking) {
                if ($booking->status === LessonBooking::COMPLETED) {
                    return true;
                }
            }
        }

        return false;
    }

}

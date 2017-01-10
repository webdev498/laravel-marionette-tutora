<?php namespace App\Transformers;

use App\LessonBooking;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class LessonBookingTransformer extends TransformerAbstract
{

    /**
     * List of default resources to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'schedule',
        'student',
        'subject',
    ];

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(LessonBooking $booking)
    {
        return [
            'uuid'       => (string)  $booking->uuid,
            'date'       => (string)  $booking->start_at->format('d/m/Y'),
            'time'       => (string)  $booking->start_at->format('H:i'),
            'duration'   => (string)  gmdate('H:i', $booking->duration),
            'location'   => (string)  $booking->location,
            'rate'       => (integer) $booking->rate,
            'tutor_rate' => (integer) $booking->lesson->relationship->tutor->profile->rate,
            'partial_charge' => $this->shouldBePartiallyCharged($booking),
        ];
    }

    public function includeSchedule(LessonBooking $booking)
    {
        if ($booking->lesson->schedule) {
            return $this->item($booking->lesson->schedule, function ($schedule) {
                return [
                    'repeat'      => (string)  $schedule->repeat,
                    'description' => (string)  $schedule->description,
                ];
            });
        }
    }

    /**
     * Include student data
     *
     * @param  LessonBooking $booking
     * @return Item
     */
    protected function includeStudent(LessonBooking $booking, $params)
    {
        return $this->item(
            $booking->lesson->relationship->student,
            new UserTransformer()
        );
    }

    /**
     * Include subject data
     *
     * @param  LessonBooking $booking
     * @return Item
     */
    protected function includeSubject(LessonBooking $booking)
    {
        return $this->item(
            $booking->lesson->subject,
            new SubjectTransformer()
        );
    }

    /**
     * Should the lesson booking be partially charged?
     *
     * @param  LessonBooking $booking
     * @return boolean
     */
    protected function shouldBePartiallyCharged(LessonBooking $booking)
    {
        $minutes = config('lesson.cancel_period', 720);
        $date    = Carbon::now()->addMinutes($minutes);

        return $booking->start_at->lt($date);
    }

}

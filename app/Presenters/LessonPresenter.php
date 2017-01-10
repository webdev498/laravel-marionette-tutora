<?php namespace App\Presenters;

use App\Lesson;
use App\LessonBooking;
use League\Fractal\TransformerAbstract;

class LessonPresenter extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'schedule',
        'bookings',
        'subject',
        'relationship',
    ];

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(Lesson $lesson)
    {
        $hours = bcdiv($lesson->duration, 3600, 2);
        $price = bcmul($lesson->rate, $hours, 2);

        return [
            'id'       => (integer) $lesson->id,
            'location' => (string) $lesson->location,
            'price'    => (string) '&pound;'.$price,
        ];
    }

    /**
     * Include schedule data
     *
     * @param  Lesson $lesson
     * @return Collection
     */
    public function includeSchedule(Lesson $lesson)
    {
        if ($lesson->schedule) {
            return $this->item($lesson->schedule, function ($schedule) {
                return [
                    'repeat'      => $schedule->repeat,
                    'description' => $schedule->description,
                ];
            });
        }
    }

    /**
     * Include bookings data
     *
     * @param  Lesson $lesson
     * @return Collection
     */
    public function includeBookings(Lesson $lesson)
    {
        return $this->collection($lesson->bookings, new LessonBookingPresenter());
    }

    /**
     * Include subject data
     *
     * @param  Lesson $lesson
     * @return Item
     */
    public function includeSubject(Lesson $lesson)
    {
        return $this->item($lesson->subject, new SubjectPresenter());
    }

    /**
     * Include relationship date
     *
     * @param  Lesson
     * @return Item
     */
    public function includeRelationship(Lesson $lesson)
    {
        return $this->item($lesson->relationship, new RelationshipPresenter());
    }

}

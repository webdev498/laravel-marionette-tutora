<?php

use App\Lesson;
use Carbon\Carbon;
use App\LessonBooking;
use App\LessonSchedule;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ( ! environment('testing')) {
            foreach ($this->getRelationships() as $relationship) {
                $tutor   = $relationship->tutor;
                $subject = $tutor->subjects->random();

                // Lesson
                $lessons = $relationship->lessons()->saveMany(
                    factory(Lesson::class, 3)->make([
                        'subject_id' => $subject->id,
                        'rate'       => $tutor->profile->rate,
                    ])
                );

                foreach ($lessons as $i => $lesson) {
                    // Schedule
                    // Use the created at date as a start point
                    $start = $lesson->created_at;
                    // Don't create a schedule for the first lesson
                    if ($i == 0) {
                        $dates = [$start];
                    } else {
                        // Make
                        $name     = $i > 1 ? 'weekly' : 'fortnightly';
                        $schedule = factory(LessonSchedule::class, $name)->make();
                        // Dates
                        $dates = $schedule->dates($start, config('booking.repeat.count', 10));
                        // Last scheduled
                        $schedule->last_run_at       = $start;
                        $schedule->last_scheduled_at = last($dates);
                        // Save
                        $lesson->schedule()->save($schedule);
                    }

                    // Bookings
                    $now = Carbon::now();
                    $lesson->bookings()->saveMany(
                        array_map(function ($start) use ($lesson, $now) {
                            // Finish
                            $finish = $start->copy()->addSeconds($lesson->duration);
                            // Price
                            $hours = bcdiv($lesson->duration, 3600, 2);
                            $price = bcmul($lesson->rate, $hours, 2);
                            // Status'
                            $diff = $start->diffInHours($now, false);
                            $status       = $diff > 0 ? LessonBooking::COMPLETED : LessonBooking::CONFIRMED;
                            $chargeStatus = $diff > 0 ? LessonBooking::PAID      : LessonBooking::PENDING;
                            // Make
                            return factory(LessonBooking::class)->make([
                                'start_at'      => $start,
                                'finish_at'     => $finish,
                                'duration'      => $lesson->duration,
                                'rate'          => $lesson->rate,
                                'price'         => $price,
                                'location'      => $lesson->location,
                                'status'        => $status,
                                'charge_status' => $chargeStatus,
                            ]);
                        }, $dates)
                    );
                }
            }
        }
    }
}

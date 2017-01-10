<?php

use App\Lesson;
use App\Subject;
use Carbon\Carbon;
use App\Relationship;
use App\LessonBooking;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompleteLessonBookingsCommandTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_completes_lessons_in_the_past()
    {
        $this->withoutEvents();
        // Models
        $subject      = Subject::find(3);
        $relationship = Relationship::first();
        // Data
        $deets = [
            // Should complete
            [
                'date'          => Carbon::now()->subDays(1),
                'status'        => LessonBooking::CONFIRMED,
                'charge_status' => LessonBooking::AUTHORISED,
            ],
            // Shouldn't complete
            [
                'date'          => Carbon::now()->addDays(1),
                'status'        => LessonBooking::CONFIRMED,
                'charge_status' => LessonBooking::AUTHORISED,
            ],
            [
                'date'          => Carbon::now()->subDays(1),
                'status'        => LessonBooking::CONFIRMED,
                'charge_status' => LessonBooking::PENDING,
            ],
            [
                'date'          => Carbon::now()->subDays(1),
                'status'        => LessonBooking::PENDING,
                'charge_status' => LessonBooking::PENDING,
            ],
        ];
        // Lessons
        foreach ($deets as $deet) {
            // Deet
            $date         = array_get($deet, 'date');
            $status       = array_get($deet, 'status');
            $chargeStatus = array_get($deet, 'charge_status');
            // Tiem
            $date->hour   = 13;
            $date->minute = 0;
            $date->second = 0;
            // Lesson
            $lesson = factory(Lesson::class)->create([
                'relationship_id' => $relationship->id,
                'subject_id'      => $subject->id,
                'status'          => $status,
            ]);
            // Booking
            $booking = factory(LessonBooking::class)->create([
                'lesson_id'     => $lesson->id,
                'start_at'      => $date,
                'duration'      => $lesson->duration,
                'rate'          => $lesson->rate,
                'location'      => $lesson->location,
                'status'        => $status,
                'charge_status' => $chargeStatus,
            ]);
        }
        // Run command
        $this->artisan('tutora:complete_lesson_bookings');
        // Tests
        // One lesson completed
        $completed = (new LessonBooking())
            ->newQuery()
            ->where('status', '=', LessonBooking::COMPLETED)
            ->count();
        $this->assertEquals(1, $completed);
        // Three lesons haven't
        $incompleted = (new LessonBooking())
            ->newQuery()
            ->where('status', '!=', LessonBooking::COMPLETED)
            ->count();
        $this->assertEquals(3, $incompleted);
    }
}

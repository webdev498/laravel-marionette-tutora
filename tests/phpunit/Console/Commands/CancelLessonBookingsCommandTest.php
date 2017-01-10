<?php

use App\Lesson;
use App\Subject;
use Carbon\Carbon;
use App\Relationship;
use App\LessonBooking;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CancelLessonBookingsCommandTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_cancels_lessons_in_the_past()
    {
        $this->withoutEvents();
        // Models
        $subject      = Subject::find(3);
        $relationship = Relationship::first();
        // Data
        $deets = [
            // Should cancel
            [
                'date'          => Carbon::now(),
                'status'        => LessonBooking::PENDING,
                'charge_status' => LessonBooking::PENDING,
            ],
            [
                'date'          => Carbon::now(),
                'status'        => LessonBooking::CONFIRMED,
                'charge_status' => LessonBooking::PENDING,
            ],
            // Shouldn't cancel
            [
                'date'          => Carbon::now()->addDays(1),
                'status'        => LessonBooking::PENDING,
                'charge_status' => LessonBooking::PENDING,
            ],
        ];
        // Lessons
        foreach ($deets as $i => $deet) {
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
                'location'        => $i,
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
        $this->artisan('tutora:cancel_lesson_bookings');
        // Tests
        // Lessons cancelled
        $cancelled = (new LessonBooking())
            ->newQuery()
            ->where('status', '=', LessonBooking::CANCELLED)
            ->get();
        $this->assertEquals(2, $cancelled->count());
        $locations = $cancelled->lists('location')->toArray();
        $this->assertContains(0, $locations);
        $this->assertContains(1, $locations);
        // Lessons didn't cancel
        $cancelled = (new LessonBooking())
            ->newQuery()
            ->where('status', '!=', LessonBooking::CANCELLED)
            ->get();
        $this->assertEquals(1, $cancelled->count());
        $locations = $cancelled->lists('location')->toArray();
        $this->assertContains(2, $locations);
    }
}

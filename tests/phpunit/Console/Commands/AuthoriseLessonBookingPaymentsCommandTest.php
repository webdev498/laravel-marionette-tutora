<?php

use App\Lesson;
use App\Subject;
use Carbon\Carbon;
use App\Relationship;
use App\LessonBooking;
use Test\Stub\Billing\StubBilling;
use App\Billing\Contracts\BillingInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthoriseLessonBookingPaymentsCommandTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->app->bind(
            BillingInterface::class,
            StubBilling::class
        );
    }

    public function test_it_authorises_a_lesson()
    {
        $this->withoutEvents();
        // Models
        $subject      = Subject::find(3);
        $relationship = Relationship::first();
        // Card
        $relationship->student->last_four = 9455;
        $relationship->student->save();
        // Date
        $date = Carbon::now()->subDays(2);
        $date->hour   = 13;
        $date->minute = 0;
        $date->second = 0;
        // Lesson
        $lesson = factory(Lesson::class)->create([
            'relationship_id' => $relationship->id,
            'subject_id'      => $subject->id,
            'status'          => Lesson::CONFIRMED,
        ]);
        // Booking
        $booking = factory(LessonBooking::class)->create([
            'lesson_id'     => $lesson->id,
            'start_at'      => $date,
            'duration'      => $lesson->duration,
            'rate'          => $lesson->rate,
            'location'      => $lesson->location,
            'status'        => LessonBooking::CONFIRMED,
            'charge_status' => LessonBooking::AUTHORISATION_PENDING,
        ]);
        // Run command
        $command = app('App\Console\Commands\AuthoriseLessonBookingPaymentsCommand');
        $command->fire();
        // Test
        $authorised = (new LessonBooking())
            ->newQuery()
            ->where('charge_status', '=', LessonBooking::AUTHORISED)
            ->get();
        $this->assertEquals(1, $authorised->count());
    }

    public function test_it_fails_authorisation()
    {
        $this->withoutEvents();
        // Models
        $subject      = Subject::find(3);
        $relationship = Relationship::first();
        // Card
        $relationship->student->last_four = 3417;
        $relationship->student->save();
        // Date
        $date = Carbon::now()->subDays(2);
        $date->hour   = 13;
        $date->minute = 0;
        $date->second = 0;
        // Lesson
        $lesson = factory(Lesson::class)->create([
            'relationship_id' => $relationship->id,
            'subject_id'      => $subject->id,
            'status'          => Lesson::CONFIRMED,
        ]);
        // Booking
        $booking = factory(LessonBooking::class)->create([
            'lesson_id'     => $lesson->id,
            'start_at'      => $date,
            'duration'      => $lesson->duration,
            'rate'          => $lesson->rate,
            'location'      => $lesson->location,
            'status'        => LessonBooking::CONFIRMED,
            'charge_status' => LessonBooking::AUTHORISATION_PENDING,
        ]);
        // Run command
        $command = app('App\Console\Commands\AuthoriseLessonBookingPaymentsCommand');
        $command->fire();
        // Test
        $authorised = (new LessonBooking())
            ->newQuery()
            ->where('charge_status', '=', LessonBooking::AUTHORISATION_FAILED)
            ->get();
        $this->assertEquals(1, $authorised->count());
    }
}

<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\LessonBooking;
use Illuminate\Database\DatabaseManager;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class CancelLessonBookingsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:cancel_lesson_bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel lesson bookings that havent even been attempted to be paid for.';

    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * Create a new command instance.
     *
     * @param  DatabaseManager                  $database
     * @param  LessonBookingRepositoryInterface $bookings
     * @return void
     */
    public function __construct(
        DatabaseManager                  $database,
        LessonBookingRepositoryInterface $bookings
    ) {
        parent::__construct();

        $this->database = $database;
        $this->bookings = $bookings;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        loginfo("[ Background ] {$this->name}");

        $this->database->transaction(function () {
            $date     = $this->getDate();
            $bookings = $this->bookings->getCancellableBeforeDate($date);

            foreach ($bookings as $booking) {
                LessonBooking::expire($booking);
                $this->bookings->save($booking);
                $this->dispatchFor($booking);
            }
        });
    }

    /**
     * Get the date to grab lessons by
     *
     * @return Carbon
     */
    protected function getDate()
    {
        $minutes = config('lessons.cancel_period', 720);

        return Carbon::now()->addMinutes($minutes);
    }

}

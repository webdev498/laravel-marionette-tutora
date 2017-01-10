<?php namespace App\Billing;

use App\Tutor;
use Carbon\Carbon;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class FeeCalculator
{
    /**
    * @var LessonBookingRepositoryInterface
    */
    protected $bookings;

    /**
     * Create a new command instance.
     *
     * @param  DatabaseManager                  $database
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  BillingInterface                 $billing
     * @return void
     */
    public function __construct(
        LessonBookingRepositoryInterface $bookings
    ) {
        $this->bookings = $bookings;
    }

    /**
     * Calculate the fee owed by a given tutor
     *
     * @param  Tutor
     * @return mixed
     */
    public function calculateByTutor(Tutor $tutor)
    {
        $date  = $this->getDate();
        $count = $this->getLessonCount($tutor, $date);

        $fee = $this->getFee($count);

        // Fix for Rob Last
        if ($tutor->uuid == '54okddqr') $fee = '15%';

        return $fee;
    }

    /**
     * Get the fee for the given number of lessons
     *
     * @param  integer $count
     * @return mixed
     */
    protected function getFee($count)
    {
        $ranges = config('fees.ranges');

        foreach ($ranges as $range) {
            $fee = array_get($range, 'fee');
            $min = array_get($range, 'range.min');
            $max = array_get($range, 'range.max');

            $test = filter_var($count, FILTER_VALIDATE_INT, [
                'options' => array_filter([
                    'min_range' => $min,
                    'max_range' => $max,
                ], function ($value) {
                    return $value !== null;
                }),
            ]);

            if ($test !== false) {
                return $fee;
            }
        }

        return array_get($ranges, '0.fee');
    }

    /**
     * Get the date of which to count from
     *
     * @return DateTime
     */
    protected function getDate()
    {
        $days = config('fees.period');
        return Carbon::now()->subDays($days);
    }

    /**
     * Get the number of lessons a tutor has taught
     *
     * @param  Tutor $tutor
     * @param  Carbon $date
     * @return integer
     */
    protected function getLessonCount(Tutor $tutor, Carbon $date)
    {
        return $this->bookings->countCompletedAfterDateByTutor($tutor, $date);
    }
}

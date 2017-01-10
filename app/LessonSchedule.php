<?php namespace App;

use DateTime;
use Carbon\Carbon;
use App\Database\Model;
use Cron\CronExpression;
use App\Events\LessonScheduleWasMade;
use App\Events\LessonScheduleWasEdited;

class LessonSchedule extends Model
{
    const NEVER       = 'never';
    const WEEKLY      = 'weekly';
    const FORTNIGHTLY = 'fortnightly';

    /**
     * The attributes to append to the model's array and JSON form.
     *
     * @var array
     */
    protected $appends = [
        'repeat',
        'description',
    ];

    protected $dates = [
        'last_scheduled_at',
    ];

    /**
     * @var CronExpression
     */
    protected $cron;

    /**
     * Create a weekly schedule.
     *
     * @param  DateTime $date
     * @return LessonSchedule
     */
    public static function weekly(DateTime $date)
    {
        $schedule = new static();

        $schedule->minute           = $date->minute;
        $schedule->hour             = $date->hour;
        $schedule->day_of_the_month = -1;
        $schedule->month            = -1;
        $schedule->day_of_the_week  = $date->dayOfWeek;
        $schedule->nth              = 1;

        $schedule->raise(new LessonScheduleWasMade($schedule));

        return $schedule;
    }

    /**
     * Create a fortnightly schedule.
     *
     * @param  DateTime $date
     * @return LessonSchedule
     */
    public static function fortnightly(DateTime $date)
    {
        $schedule = new static();

        $schedule->minute           = $date->minute;
        $schedule->hour             = $date->hour;
        $schedule->day_of_the_month = -1;
        $schedule->month            = -1;
        $schedule->day_of_the_week  = $date->dayOfWeek;
        $schedule->nth              = 2;

        $schedule->raise(new LessonScheduleWasMade($schedule));

        return $schedule;
    }

    /**
     * Edit a schedule.
     *
     * @param  DateTime $date
     * @return LessonSchedule
     */
    public static function edit(LessonSchedule $schedule, DateTime $date)
    {
        $schedule->minute           = $date->minute;
        $schedule->hour             = $date->hour;
        $schedule->day_of_the_month = -1;
        $schedule->month            = -1;
        $schedule->day_of_the_week  = $date->dayOfWeek;

        $schedule->raise(new LessonScheduleWasEdited($schedule));

        return $schedule;
    }

    /**
     * Update the last_scheduled_at and last_run_at timestamps.
     *
     * @param  LessonSchedule $schedule
     * @param  DateTime       $date
     * @return LessonSchedule
     */
    public static function updateLastScheduledAt(
        LessonSchedule $schedule,
        DateTime       $date
    ) {
        $schedule->last_run_at       = Carbon::now();
        $schedule->last_scheduled_at = $date;

        return $schedule;
    }

    /**
     * Return an instance of CronExpression based on the attributes
     * of this LessonSchedule
     *
     * @return CronExpression
     */
    public function cron()
    {
        $expression = sprintf(
            '%s %s %s %s %s',
            $this->minute,
            $this->hour,
            $this->day_of_the_month,
            $this->month,
            $this->day_of_the_week
        );

        // Return a cached expression
        if (isset($this->cron) && $this->cron->getExpression() === $expression) {
            return $this->cron;
        }

        return $this->cron = CronExpression::factory($expression);
    }

    /**
     * Get dates from, and including a starting date based on this schedule.
     *
     * @param  DateTime $start
     * @param  Integer  $count the number of dates to return
     * @return Array
     */
    public function dates(DateTime $start, $count, $includeStart = true)
    {
        if ($count < 1) {
            throw new \InvalidArgumentException('Count must be a positive integer');
        }

        $range = $count === 1
            ? [$count]
            : range(1, $count * $this->nth, $this->nth);
        $dates = [];

        foreach ($range as $nth) {
            // Workaround for "impossible CRON expression" bug.
            // https://github.com/mtdowling/cron-expression/issues/66
            try {
                $dates[] = $this->cron()->getNextRunDate($start, $nth - 1, $includeStart);
            } catch (\Exception $e) {
                continue;
            }
        }

        return $dates;
    }

    /**
     * Each schedule belongs to one lesson.
     *
     * @return BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo('App\Lesson');
    }

    /**
     * Make laravel return these as Carbon instances too.
     *
     * @return Array
     */
    public function getDates()
    {
        return array_merge(parent::getDates(), [
            'last_run_at',
            'last_scheduled_at',
        ]);
    }

    /**
     * Get the repeat attribute
     *
     * @return string
     */
    public function getRepeatAttribute()
    {
        if ($this->day_of_the_week >= 0) {
            if ($this->nth === 1) {
                return static::WEEKLY;
            } elseif ($this->nth === 2) {
                return static::FORTNIGHTLY;
            }
        }
    }

    /**
     * Get the description attribute
     *
     * @return string
     */
    public function getDescriptionAttribute()
    {
        $description = null;
        $next        = $this->cron()->getNextRunDate();

        switch ($this->repeat) {
            case static::WEEKLY:
                $description = $next->format('\w\e\e\k\l\y \o\n l \a\t H:i');
                break;

            case static::FORTNIGHTLY:
                $description = $next->format('\f\o\r\t\n\i\g\h\t\l\y \o\n l \a\t H:i');
                break;
        }

        return $description;
    }

    /**
     * Mutate the minute value to a cron representation.
     *
     * @param  String $value
     * @return String
     */
    public function getMinuteAttribute($value)
    {
        return $this->valueAsCronExpressionValue($value);
    }

    /**
     * Mutate the hour value to a cron representation.
     *
     * @param  String $value
     * @return String
     */
    public function getHourAttribute($value)
    {
        return $this->valueAsCronExpressionValue($value);
    }

    /**
     * Mutate the day of the month value to a cron representation.
     *
     * @return String
     */
    public function getDayOfTheMonthAttribute($value)
    {
        return $this->valueAsCronExpressionValue($value);
    }

    /**
     * Mutate the month value to a cron representation.
     *
     * @param  String $value
     * @return String
     */
    public function getMonthAttribute($value)
    {
        return $this->valueAsCronExpressionValue($value);
    }

    /**
     * Mutate the day of the week value to a cron representation.
     *
     * @param  String $value
     * @return String
     */
    public function getDayOfTheWeekAttribute($value)
    {
        return $this->valueAsCronExpressionValue($value);
    }

    /**
     * Return the given value value as it's cron representation.
     *
     * @param  String $value
     * @return String
     */
    protected function valueAsCronExpressionValue($value)
    {
        if ($value === -1) {
            return '*';
        }

        return $value;
    }

}

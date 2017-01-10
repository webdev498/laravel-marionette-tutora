<?php namespace App;

use App\Billing\ChargableTrait;
use App\Billing\Contracts\ChargableInterface;
use App\Billing\Contracts\ChargeInterface;
use App\Billing\Contracts\Exceptions\BillingExceptionInterface;
use App\Billing\TransferrableTrait;
use App\Database\Model;
use App\Events\LessonBookingChargeAuthorisationFailed;
use App\Events\LessonBookingChargeAuthorisationSucceeded;
use App\Events\LessonBookingChargePaid;
use App\Events\LessonBookingChargePaymentFailed;
use App\Events\LessonBookingHasExpired;
use App\Events\LessonBookingTransferFailed;
use App\Events\LessonBookingWasCancelled;
use App\Events\LessonBookingWasCompleted;
use App\Events\LessonBookingWasConfirmed;
use App\Events\LessonBookingWasEdited;
use App\Events\LessonBookingWasMade;
use App\Events\LessonBookingWasRefunded;
use App\Observers\LessonBookingObserver;
use App\User;
use DB;
use DateTime;
use Illuminate\Database\Eloquent\Builder;

class LessonBooking extends Model implements
    ChargableInterface
{

    use ChargableTrait;
    use TransferrableTrait;

    const PENDING               = 'pending';
    const CONFIRMED             = 'confirmed';
    const AUTHORISED            = 'authorised';
    const AUTHORISATION_PENDING = 'authorisation_pending';
    const AUTHORISATION_FAILED  = 'authorisation_failed';
    const PAID                  = 'paid';
    const PAYMENT_PENDING       = 'payment_pending';
    const PAYMENT_FAILED        = 'payment_failed';
    const PAID_PARTIALLY        = 'paid_partially';
    const REFUNDED              = 'refunded';
    const REFUNDED_PARTIALLY    = 'refunded_partially';
    const COMPLETED             = 'completed';
    const CANCELLED             = 'cancelled';
    const UPCOMING              = 'upcoming';
    
    //Transfer Statuses
    const TRANSFER_PENDING      = 'pending';
    const TRANSFERRED           = 'transferred';
    const TRANSFER_FAILED       = 'failed';
    const TRANSFER_IN_TRANSIT   = 'in_transit';

    const ACTIVE_STATUSES = [
        self::PENDING,
        self::CONFIRMED,
    ];

    /**
     * Request laravel transform these attributes into
     * Carbon instances
     *
     * @var array
     */
    protected $dates = [
        'start_at',
        'finish_at',
        'last_attempted_at',
        'transferred_at',
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        parent::observe(new LessonBookingObserver);
    }

    /**
     * Create a booking instance.
     *
     * @param  stirng   $uuid
     * @param  DateTime $start
     * @param  Lesson   $lesson
     * @return LessonBooking
     */
    public static function make(
        $uuid,
        DateTime $start,
        Lesson   $lesson
    ) {
        $booking = new static();

        $booking->uuid     = $uuid;
        $booking->start_at = $start;
        $booking->duration = $lesson->duration;
        $booking->rate     = $lesson->rate;
        $booking->location = $lesson->location;
        $booking->status   = $lesson->status === Lesson::CONFIRMED
            ? static::CONFIRMED
            : static::PENDING;
        $booking->charge_status = $lesson->status === Lesson::CONFIRMED
            ? static::AUTHORISATION_PENDING
            : static::PENDING;

        $booking->raise(new LessonBookingWasMade($booking));

        return $booking;
    }

    /**
     * Edit a booking instance.
     *
     * @param  LessonBooking $booking
     * @param  DateTime $startAt
     * @param  Integer  $duration in seconds.
     * @param  Integer  $rate     in pounds.
     * @param  String   $location
     * @return LessonBooking
     */
    public static function edit(
        LessonBooking $booking,
        DateTime      $startAt,
        $duration,
        $rate,
        $location
    ) {
        $booking->start_at = $startAt;
        $booking->location = $location;
        $booking->duration = $duration;
        $booking->rate     = $rate;

        $booking->raise(new LessonBookingWasEdited($booking));

        return $booking;
    }

    /**
     * Cancel a booking instance.
     *
     * @param  LessonBooking $booking
     * @return LessonBooking
     */
    public static function cancel(LessonBooking $booking)
    {
        // Attributes
        $booking->status        = static::CANCELLED;
        $booking->charge_status = static::CANCELLED;
        // Raise
        $booking->raise(new LessonBookingWasCancelled($booking));
        // Return
        return $booking;
    }

    /**
     * Expire (cancel) a booking instance.
     *
     * @param  LessonBooking $booking
     * @return LessonBooking
     */
    public static function expire(LessonBooking $booking)
    {
        // Attributes
        $booking->status        = static::CANCELLED;
        $booking->charge_status = static::CANCELLED;
        // Raise
        $booking->raise(new LessonBookingHasExpired($booking));
        // Return
        return $booking;
    }


    /**
     * Confirm a booking instance.
     *
     * @param  LessonBooking $booking
     * @return LessonBooking
     */
    public static function confirm(LessonBooking $booking)
    {
        // Attributes
        $booking->status        = static::CONFIRMED;
        $booking->charge_status = static::AUTHORISATION_PENDING;
        // Raise
        $booking->raise(new LessonBookingWasConfirmed($booking));
        // Return
        return $booking;
    }

    /**
     * Complete a booking instance.
     *
     * @param  LessonBooking $booking
     * @return LessonBooking
     */
    public static function complete(LessonBooking $booking)
    {
        // Attributes
        $booking->status = static::COMPLETED;
        // Riase
        $booking->raise(new LessonBookingWasCompleted($booking));
        // Return
        return $booking;
    }

    /**
     * Authorised charge on the booking instance.
     *
     * @param  LessonBooking   $booking
     * @param  ChargeInterface $charge
     * @return LessonBooking
     */
    public static function authorised(
        LessonBooking   $booking,
        ChargeInterface $charge
    ) {
        // Attributes
        $booking->setChargeId($charge->id);
        $booking->setChargeStatus(static::AUTHORISED);
        $booking->raise(new LessonBookingChargeAuthorisationSucceeded($booking));
        // Return
        return $booking;
    }

    /**
     * Authorisation failed on the booking instance.
     *
     * @param  LessonBooking             $booking
     * @param  BillingExceptionInterface $e
     */
    public static function authorisationFailed(
        LessonBooking             $booking,
        DateTime                  $date,
        BillingExceptionInterface $e
    ) {
        // Attributes
        $booking->setChargeStatus(static::AUTHORISATION_FAILED);
        $booking->payment_attempts++;
        $booking->last_attempted_at = $date;
        // Raise
        $booking->raise(new LessonBookingChargeAuthorisationFailed($booking, $e));
        // Return
        return $booking;
    }

    /**
     * Captured charge on the booking instance.
     *
     * @param  LessonBooking   $booking
     * @param  ChargeInterface $charge
     * @return LessonBooking
     */
    public static function paid(LessonBooking $booking, ChargeInterface $charge)
    {
        // Attributes
        $booking->setChargeStatus(static::PAID);
        $booking->setChargeId($charge->id);
        // Raise
        $booking->raise(new LessonBookingChargePaid($booking));
        // Return
        return $booking;
    }

    /**
     * Captured charge on the booking instance.
     *
     * @param  LessonBooking   $booking
     * @param  ChargeInterface $charge
     * @return LessonBooking
     */
    public static function paidPartially(LessonBooking $booking, ChargeInterface $charge)
    {
        // Attributes
        $booking->setChargeStatus(static::PAID_PARTIALLY);
        $booking->setChargeId($charge->id);
        // Return
        return $booking;
    }

    /**
     * Capturing the charge failed
     *
     * @param  LessonBooking             $booking
     * @param  BillingExceptionInterface $e
     * @return LessonBooking
     */
    public static function paymentFailed(
        LessonBooking             $booking,
        DateTime                  $date,
        BillingExceptionInterface $e
    ) {
        // Attributes
        $booking->setChargeStatus(static::PAYMENT_FAILED);
        $booking->payment_attempts++;
        $booking->last_attempted_at = $date;

        // Raise
        $booking->raise(new LessonBookingChargePaymentFailed($booking, $e));
        // Return
        return $booking;
    }

    /**
     * Set a failed charge to be reauthorised
     *
     * @param  LessonBooking $booking
     * @return LessonBooking
     */
    public static function reauthorise(LessonBooking $booking)
    {
        // Attributes
        $booking->setChargeStatus(static::AUTHORISATION_PENDING);
        $booking->setChargeId(null);
        // Return
        return $booking;
    }

    /**
     * Set a failed charge to be recaptured
     *
     * @param  LessonBooking $booking
     * @return LessonBooking
     */
    public static function recapture(LessonBooking $booking)
    {
        // Attributes
        $booking->setChargeStatus(static::PAYMENT_PENDING);
        $booking->setChargeId(null);
        // Return
        return $booking;
    }

    /**
     * Refunded on the booking instance.
     *
     * @param  LessonBooking   $booking
     * @param  ChargeInterface $charge
     * @return LessonBooking
     */
    public static function refunded(LessonBooking $booking)
    {
        // Attributes
        $booking->setChargeStatus(static::REFUNDED);
        $booking->status = static::CANCELLED;
        // Raise
        $booking->raise(new LessonBookingWasRefunded($booking));
        // Return
        return $booking;
    }

    /**
     * Refunded on the booking instance.
     *
     * @param  LessonBooking   $booking
     * @param  ChargeInterface $charge
     * @return LessonBooking
     */
    public static function refundedPartially(LessonBooking $booking)
    {
        // Attributes
        $booking->setChargeStatus(static::REFUNDED_PARTIALLY);
        $booking->status = static::CANCELLED;
        // Raise
        $booking->raise(new LessonBookingWasRefunded($booking));
        // Return
        return $booking;
    }

    /**
     * Transfer Started to Tutor bank account
     *
     * @param  LessonBooking   $booking
     * @param  DateTime        $date
     * @return LessonBooking
     */
    public static function transferInTransit(LessonBooking $booking, $date)
    {
        // Attributes
        $booking->setTransferStatus(static::TRANSFER_IN_TRANSIT);
        $booking->transferred_at = $date;

        // Return
        return $booking;
    }


    /**
     * Transfered charge to Tutor bank account
     *
     * @param  LessonBooking   $booking
     * @return LessonBooking
     */
    public static function transferred(LessonBooking $booking, $date)
    {
        // Attributes
        $booking->setTransferStatus(static::TRANSFERRED);
        $booking->transferred_at = $date;     
        //Raise
        // $booking->raise(new LessonBookingTransferSucceeded)
        // Return
        return $booking;
    }

    /**
     * Transfer to Tutor bank account Failed
     *
     * @param  LessonBooking   $booking
     * @return LessonBooking
     */
    public static function transferFailed(LessonBooking $booking)
    {
        // Attributes
        $booking->setTransferStatus(static::TRANSFER_FAILED);
        // Raise
        $booking->raise(new LessonBookingTransferFailed($booking));
        // Return 
        return $booking;
    }

    /**
     * Each booking belongs to one lesson
     *
     * @return BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo('App\Lesson');
    }

    /**
     * Each booking morphs to many reminders
     *
     * @return MorphMany
     */

    public function reminders()
    {
        return $this->morphMany('App\Reminder', 'remindable');
    }

    public function relationship()
    {
        $lesson = $this->lesson;
        return $lesson->relationship;
    }

    public function getStudentAttribute()
    {
        $lesson = $this->lesson;
        $relationship = $lesson->relationship;
        return $relationship->student;
    }

    public function getTutorAttribute()
    {
        $lesson = $this->lesson;
        $relationship = $lesson->relationship;
        return $relationship->tutor;
    }

    public function scopeAddSelectHasPassed(Builder $query)
    {
        return $query->addSelect(DB::raw('((`finish_at` - NOW()) < 0) as `has_passed`'));
    }

    public function scopeHasRelationship(Builder $query, Relationship $relationship)
    {
        return $query->whereHas('lesson.relationship', function ($q) use ($relationship) {
            return $q->where('id', '=', $relationship->id);
        });
    }

    public function scopeHasTutor(Builder $query, User $tutor)
    {
        return $query->whereHas('lesson.relationship.tutor', function ($q) use ($tutor) {
            return $q->where('id', '=', $tutor->id);
        });
    }

    public function scopeHasStudent(Builder $query, User $student)
    {
        return $query->whereHas('lesson.relationship.student', function ($q) use ($student) {
            return $q->where('id', '=', $student->id);
        });
    }

    public function scopeWhereHasntPassed(Builder $query)
    {
        return $query->where(DB::raw('((`finish_at` - NOW()) < 0)'), '=', 0);
    }

    public function scopeOrderByHasPassed(Builder $query)
    {
        return $query->orderBy('has_passed', 'ASC');
    }

    public function scopeOrderByFinishAt(Builder $query)
    {
        return $query->orderBy(DB::raw('ABS(`finish_at` - NOW())'), 'ASC');
    }

    public function scopeStatus(Builder $query, $status)
    {
        if ($status == static::UPCOMING) {
            return $query->where(function($query) {
                return $query->where('status', '=', static::PENDING)->orWhere('status', '=', static::CONFIRMED);
            });
        }

        return $query->where('status', '=', $status);
    }

    public function isPending()
    {
        return $this->status === static::PENDING;
    }

    public function isCancelled()
    {
        return $this->status === static::CANCELLED;
    }

    public function isConfirmed()
    {
        return $this->status === static::CONFIRMED;
    }

    public function isCompleted()
    {
        return $this->status === static::COMPLETED;
    }

    public function hasPast()
    {
        return $this->finish_at->isPast();
    }

    public function hasFailed()
    {
        return in_array($this->charge_status, [
            LessonBooking::AUTHORISATION_FAILED,
            LessonBooking::PAYMENT_FAILED,
        ]);
    }

    public function getChargeAmountName()
    {
        return 'price';
    }

    public function getChargeDescription()
    {
        return "Tutora: {$this->uuid};";
    }

}

<?php namespace App;

use App\Database\Model;
use App\Events\Jobs\JobWasCreated;
use App\Events\Jobs\JobWasMadeClosed;
use App\Events\Jobs\JobWasMadeLive;
use App\Events\Jobs\JobWasMadePending;
use App\JobCollection;
use App\Location;
use App\Student;
use App\Subject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{

    const STATUS_PENDING = 1;
    const STATUS_LIVE    = 2;
    const STATUS_CLOSED  = 3;
    const STATUS_EXPIRED = 4;
    const STATUS_NEW     = 5;
    const STATUS_RESERVED= 6;

    const CLOSED_FOR_EXPIRATION   = 'expiration';
    const CLOSED_FOR_APPLICATIONS = 'applications';
    const CLOSED_FOR_CONFIRMATION = 'confirmation';
    const CLOSED_FOR_MANUAL       = 'manual';

    const MAX_APPLIES_COUNT = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'closed_for',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tuition_jobs';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'opened_at',
        'closed_at',
    ];
    
    /**
     * Each job has one subject
     *
     * @return BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo('App\Subject');
    }

    /**
     * Each job has one student
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get all of the job location.
     */
    public function locations()
    {
        return $this->morphToMany('App\Location', 'locatable');
    }

    /**
     * The message lines that belong to the job.
     */
    public function messageLines()
    {
        return $this->belongsToMany('App\MessageLine', 'tuition_job_message_line');
    }

    /**
     * The tutors that belong to the job.
     */
    public function tutors()
    {
        return $this->belongsToMany('App\Tutor', 'tuition_job_tutor', 'job_id', 'user_id')
            ->withPivot(['favourite', 'applied'])
            ->withTimestamps();
    }

    /**
     * Each job morphs to many reminders
     *
     * @return MorphMany
     */

    public function reminders()
    {
        return $this->morphMany('App\Reminder', 'remindable');
    }

    /**
     * Count applied tutors
     *
     * @return integer
     */
    public function getAppliedTutorsCount()
    {
        $query = $this->tutors()->wherePivot('applied', '=', true);

        return $query->count();
    }

    /**
     * Has the job been confirmed?
     *
     * @return integer
     */
    public function isConfirmed()
    {
        $messageLines = $this->messageLines;

        foreach ($messageLines as $messageLine)
        {
            $message = $messageLine->message;
            $relationship = $message->relationship;
            if ($relationship->is_confirmed) {
                return true;
            }
        }

        return false;
    }

    public function repliedToApplicant()
    {
        $tutors = $this->tutors;
        $lines = $this->messageLines;
        foreach ($lines as $line)
        {
            $message = $line->message;
            $tutor = $message->relationship->tutor;

            if ($message->hasReply() && $this->tutors()->whereUuid($tutor->uuid)->count() != 0) {
                return true;
            } 
        }    
        return false;
    }

    /**
     * Has the job been confirmed?
     *
     * @return integer
     */
    public function isConfirmedByApplicant()
    {
        $messageLines = $this->messageLines()->where('message_lines.user_id', '!=', $this->user_id)->get();

        foreach ($messageLines as $messageLine)
        {
            if ($messageLine)
            $message = $messageLine->message;
            $relationship = $message->relationship;
            if ($relationship->is_confirmed) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the enquiry for a job
     *
     * @return Enquiry $enquiry
     */
    public function enquiry() 
    {
        $line =  $this
            ->messageLines()
            ->where('message_lines.user_id', '=', $this->user_id)
            ->first();

        return $line->message->relationship;
    }

    /**
     * Get the applications (extends relationships) to a job
     *
     * @return Collection $applications
     */
    public function applications() 
    {
        $messageLines =  $this
            ->messageLines()
            ->where('message_lines.user_id', '!=', $this->user_id)
            ->get();

        $applications = [];

        foreach ($messageLines as $line) {
            $applications[] = $line->message->relationship;
        }

        return collect($applications);
    }



    /**
     * @param array    $params
     * @param Student  $student
     * @param Subject  $subject
     *
     * @return Job
     */
    public static function make($params, Student $student, Subject $subject = null, Tutor $tutor = null)
    {
        $job = new static();

        $job->fill($params);

        $job->status = static::STATUS_NEW;

        $job->uuid   = self::generateUuid();

        $job->user()->associate($student);
        $job->by_request = $params['by_request'];

        if($subject) {
            $job->subject()->associate($subject);
        }

        // Raise
        $job->raise(new JobWasCreated($job));

        return $job;
    }

    /**
     * Set a job to it's pending state
     *
     * @param  Job $job
     *
     * @return Job
     */
    public static function makePending(Job $job)
    {
        $newStatus = static::STATUS_PENDING;

        if($job->status == $newStatus) {return $job;}

        // Attributes
        $job->status = $newStatus;

        // Raise
        $job->raise(new JobWasMadePending($job));

        // Return
        return $job;
    }

    /**
     * Set a job to it's live state
     *
     * @param  Job $job
     *
     * @return Job
     */
    public static function makeLive(Job $job)
    {
        $newStatus = static::STATUS_LIVE;

        if($job->status == $newStatus) {return $job;}

        // Attributes
        $job->status    = $newStatus;
        $job->opened_at = new \DateTime();

        // Raise
        $job->raise(new JobWasMadeLive($job));

        // Return
        return $job;
    }

    /**
     * Set a job to it's closed state
     *
     * @param  Job    $job
     * @param  string $reason
     *
     * @return Job
     */
    public static function makeClosed(Job $job, $reason = self::CLOSED_FOR_MANUAL)
    {
        $newStatus = static::STATUS_CLOSED;

        if($job->status == $newStatus) {return $job;}

        // Attributes
        $job->status     = $newStatus;
        $job->closed_for = $reason;
        $job->closed_at  = new \DateTime();

        // Raise
        $job->raise(new JobWasMadeClosed($job));

        // Return
        return $job;
    }


    /**
     * Set a job to it's closed state
     *
     * @param  Job    $job
     * @param  string $reason
     *
     * @return Job
     */
    public static function makeReserved(Job $job)
    {
        $newStatus = static::STATUS_RESERVED;

        if($job->status == $newStatus) {return $job;}

        // Attributes
        $job->status     = $newStatus;

        // Raise

        // Return
        return $job;
    }


    // SCOPES ///////////////////////////////////////////////////////////////////////////////

    public function scopeLive($query)
    {
        return $query->where('status', self::STATUS_LIVE);
    }

    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopeReserved($query)
    {
        return $query->where('status', self::STATUS_RESERVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }


    public function scopeWasLive($query)
    {
        return $query->whereNotNull('opened_at');
    }

    public function scopeNotClosed($query)
    {
        return $query->where(function ($q) {
            $q->where('status', self::STATUS_LIVE)
              ->orWhere('status',  self::STATUS_PENDING);
        });

    }

    public function scopeRecent($query)
    {
        return $query->where(function ($q) {
            $q->where('created_at', '>', config('jobs.recent_period'));
        });

    }

}

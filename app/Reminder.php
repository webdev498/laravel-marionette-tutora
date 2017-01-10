<?php

namespace App;

use DateTime;
use App\Database\Model;

class Reminder extends Model
{

    const UPCOMING      = 'upcoming';
    const STILL_PENDING = 'still_pending';
    const REVIEW        = 'review';
    const REBOOK        = 'rebook';
    const FIRSTNOREPLY  = 'first_no_reply';
    const SECONDNOREPLY = 'second_no_reply';
    const FIRSTNOREPLY_STUDENT = 'first_no_reply_student';
    const SECONDNOREPLY_STUDENT = 'second_no_reply_student';
    const GO_ONLINE     = 'go_online';

    // System reminders
    const JOB_MADE_LIVE = 'job_made_live';


    protected $dates = ['created_at', 'updated_at', 'remind_at'];

    /**
     * Make a reminder
     *
     * @param  string   $name
     * @param  DateTime $remindAt
     * @return Reminder
     */
    public static function make($name, DateTime $remindAt)
    {
        // New
        $reminder = new static();
        // Attributes
        $reminder->name      = $name;
        $reminder->remind_at = $remindAt;
        // Return
        return $reminder;
    }

    /**
     * Many things can be remindable
     *
     * @return MorphTo
     */
    public function remindable()
    {
        return $this->morphTo();
    }
}

<?php namespace App;

use App\Database\Model;
use App\Events\ApplicationMessageLineWasWritten;
use App\Events\MessageLineWasWritten;
use App\Events\StudentMessageLineWasWritten;
use App\Events\TutorMessageLineWasWritten;
use App\Observers\MessageLineObserver;
use App\Student;
use App\Tutor;

class MessageLine extends Model
{
    /**
     * Boot the model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        parent::observe(app(MessageLineObserver::class));
    }

    /**
     * Write a new message line by a user.
     *
     * @param  String $body
     * @param  User   $user
     * @return MessageLine
     */
    public static function write($body, $user = null)
    {
        $line = new static();

        $line->body = $body;

        if ($user !== null) {
            $line->user()->associate($user);
        }

        $line->raise(new MessageLineWasWritten($line));

        if ($user instanceof Tutor) $line->raise(new TutorMessageLineWasWritten($line));

        if ($user instanceof Student) $line->raise(new StudentMessageLineWasWritten($line));

        return $line;
    }

    /**
     * Write a new application message line by a user.
     *
     * @param  String $body
     * @param  Tutor  $tutor
     * @return MessageLine
     */
    public static function writeApplication($body, Tutor $tutor = null)
    {
        $line = new static();

        $line->body = $body;

        if ($tutor !== null) {
            $line->user()->associate($tutor);
        }

        $line->raise(new ApplicationMessageLineWasWritten($line));

        return $line;
    }

    /**
     * A line belongs to a message
     *
     * @return BelongsTo
     */
    public function message()
    {
        return $this->belongsTo('App\Message');
    }

    /**
     * A line belongs to a user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function reminders()
    {
        return $this->morphMany('App\Reminder', 'remindable');
    }

    /**
     * The jobs that belong to the message line.
     */
    public function jobs()
    {
        return $this->belongsToMany('App\Job', 'tuition_job_message_line');
    }

    public function hasReply()
    {
        $message = $this->message;
        $sender = $this->user;
        $sender_id = $sender->id;

        if (isset($sender_id) && $sender_id) {

            $count = $message->lines()
                ->where('user_id', '!=', $sender->id)
                ->where('created_at', '>', $this->created_at)
                ->whereNotNull('user_id')
                ->count();

        } else {
            $count = $message->lines()
                ->where('created_at', '>', $this->created_at)
                ->whereNotNull('user_id')
                ->count();
        }

        if ($count == 0) {
            return false;
        }

        return true;
        
    }

    public function isFirst()
    {
        $message = $this->message;
        if ($message->lines()->count() == 1) {
            return true;
        }

        return false;
    }

}

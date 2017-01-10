<?php namespace App;

use App\Database\Model;
use App\Events\MessageWasOpened;

class Message extends Model
{

    /**
     * Open a new message thread
     *
     * @param  String $uuid
     * @return Message
     */
    public static function open($uuid)
    {
        $message = new static();

        $message->uuid = $uuid;

        $message->raise(new MessageWasOpened($message));

        return $message;
    } 

    /**
     * A message belongs to a relationship
     *
     * @return BelongsTo
     */
    public function relationship()
    {
        return $this->belongsTo('App\Relationship');
    }

    /**
     * A message has many lines
     *
     * @return HasMany
     */
    public function lines()
    {
        return $this->hasMany('App\MessageLine');
    }

    /**
     * A message has many MessageStatuses
     *
     * @return HasMany
     */
    public function statuses()
    {
        return $this->hasMany('App\MessageStatus');
    }

    /**
     * A message has many Transgressions
     *
     * @return HasMany
     */
    public function transgressions()
    {
        return $this->hasMany('App\Transgression');
    }

    public function status($user)
    {
         return $this->statuses()->user($user)->first();
    }

    public function hasReply()
    {
        $replies = $this->countReplies();
        
        if ($replies) return true;

        return false;
    }

    public function countReplies() {
        if ($this->lines->count() ) {
            $sender_id = $this->lines()->first()->user_id;
        }

        if (isset($sender_id) && $sender_id) {
            $replies = $this->lines()
                ->where('user_id', '!=', $sender_id)
                ->whereNotNull('user_id')
                ->count();
        } else  {
            $replies = $this->lines()
                ->whereNotNull('user_id')
                ->count();
        }

        return $replies;
    }

    public function countSenderLines() {
        if ($this->lines->count() ) {
            $sender_id = $this->lines()->first()->user_id;
        }

        if (isset($sender_id) && $sender_id) {
            $replies = $this->lines()
                ->where('user_id', $sender_id)
                ->whereNotNull('user_id')
                ->count();
        } else  {
            $replies = $this->lines()
                ->whereNotNull('user_id')
                ->count();
        }

        return $replies;
    }
}

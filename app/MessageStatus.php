<?php

namespace App;

use App\Database\Model;

class MessageStatus extends Model
{

    /**
     * A MessageStatus has one message
     *
     * @return MorphTo
     */
    public function message()
    {
        return $this->hasOne('App\Message');
    }

    /**
     * Make a MessageStatus.
     *
     * @param  Tutor   $tutor
     * @param  Student $student
     * @return Relationship
     */
    public static function make(Message $message, User $user)
    {
        // New
        $messageStatus = new static();

        // Attributes
        $messageStatus->message_id = $message->id;
        $messageStatus->user_id   = $user->id;
        $messageStatus->unread = 0;
        $messageStatus->archived = 0;

        // Return
        return $messageStatus;
    }

    public function scopeUser($query, $user){
        return $query->where('user_id', '=', $user->id);
    }

    public function markAsUnread()
    {
    	$this->unread = 1;
    	$this->save();
    }

    public function markAsRead()
    {
        $this->unread = 0;
        $this->save();
    }

}

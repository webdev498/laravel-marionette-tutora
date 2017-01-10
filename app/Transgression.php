<?php namespace App;

use App\Database\Model;

class Transgression extends Model
{

    public static function make(User $user, $body)
    {
        $transgression = new static();

        $transgression->user_id      = $user->id;
        $transgression->body        = $body;

        // $transgression->raise(new UserTransgressionWasLeft($transgression));

        return $transgression;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function message()
    {
        return $this->belongsTo('App\Message');
    }

}

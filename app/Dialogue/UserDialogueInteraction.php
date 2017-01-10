<?php

namespace App\Dialogue;

use App\Dialogue\Exceptions\DialogueException;
use Illuminate\Database\Eloquent\Model;

class UserDialogueInteraction extends Model
{
    protected $table = 'user_dialogue_interactions';
    protected $fillable = ['user_id', 'user_dialogue_id', 'duration', 'data'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function userDialogue()
    {
        return $this->belongsTo('App\Dialogue\UserDialogue');
    }

    public static function queryForUser($user, $name = NULL)
    {
        if($name === NULL)
        {
            return UserDialogueInteraction
                ::where('user_id', $user->id);
        }
        else if(is_string($name))
        {
            return UserDialogueInteraction
                ::join('user_dialogues', 'user_dialogues.id', '=', 'user_dialogue_interactions.user_dialogue_id')
                ->where('user_dialogues'.'.name', $name)
                ->where('user_id', $user->id);
        }
        else if(is_array($name))
        {
            return UserDialogueInteraction
                ::join('user_dialogues', 'user_dialogues.id', '=', 'user_dialogue_interactions.user_dialogue_id')
                ->whereIn(UserDialogue::$table.'.name', $name)
                ->where('user_id', $user->id);
        }
        else throw new DialogueException("Invalid name parameter in UserDialogueInteraction::queryForUser");
    }

    public static function getForUser($user, $name = NULL)
    {
        return static::queryForUser($user, $name)->get();
    }

    public static function countForUser($user, $name = NULL)
    {
        return static::queryForUser($user, $name)->count();
    }

    public static function existsForUser($user, $name = NULL)
    {
        return static::countForUser($user, $name) >= 1;
    }
}

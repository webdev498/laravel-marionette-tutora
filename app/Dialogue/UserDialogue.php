<?php

namespace App\Dialogue;

use Illuminate\Database\Eloquent\Model;

class UserDialogue extends Model
{
    const CUSTOM = "custom";
    const BASIC = "basic";

    protected $table = "user_dialogues";
    protected $fillable = ["name", "type", "route"];

    public function interactions()
    {
        return $this->hasMany('App\Dialogue\UserDialogueInteraction');
    }
    
    public function interactionsForUser($user)
    {
        $user_id = $user->id;
        return $this->interactions()->where('user_id', $user_id);
    }
    public function interactionsExistForUser($user)
    {
        $interactions = $this->interactionsExistForUser($user);
        return count($interactions) >= 1;
    }

    public static function Show($name, $route_parameters = [], $return_route = NULL)
    {
        $user_dialogue = static::where("name", $name)->get()->first();
        $dialogue_route = $user_dialogue->calculateRoute($route_parameters);
        return redirect($dialogue_route . ($return_route?"?return=" . urlencode($return_route):""));
    }

    public function calculateRoute($route_parameters = [])
    {
        $route_template_parts = explode("/", trim($this->route, "/"));
        $route = "";

        foreach($route_template_parts as $route_template_part)
        {
            if(strlen($route_template_part) <= 0) continue;
            $route .= "/";
            if($route_template_part[0] == ":")
            {
                if(count($route_parameters) >= 1)
                    $route .= array_shift($route_parameters);
            }
            else $route .= $route_template_part;
        }

        return $route;
    }
}

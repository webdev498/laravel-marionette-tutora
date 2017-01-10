<?php

namespace App\Http\Controllers\Api;

use App\Dialogue\UserDialogue;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Dialogue\UserDialogueInteraction;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Http\Request;
use stdClass;

class DialogueController extends Controller
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * Create an instance of the controller
     *
     * @param  Auth $auth
     */
    public function __construct(
        Auth $auth
    ) {
        $this->auth = $auth;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function routes()
    {
        $dialogues = UserDialogue::where("type", UserDialogue::BASIC)->get();
        $json = [];

        foreach($dialogues as $dialogue)
        {
            $item = new stdClass;

            $item->route_string = $dialogue->route;
            $item->name = $dialogue->name;
            $item->id = $dialogue->id;

            $json[] = $item;
        }

        return json_encode ($json, JSON_FORCE_OBJECT);
    }
    
    public function show(Request $request, $name)
    {
        $dialogueParams = config("basic_dialogues")[$name];

        $user = $this->auth->user();

        $dialogueParams['isViewed'] = UserDialogueInteraction::existsForUser($user, $name);

        return $dialogueParams;
    }
}

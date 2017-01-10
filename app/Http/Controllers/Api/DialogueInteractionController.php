<?php

namespace App\Http\Controllers\Api;

use App\Dialogue\UserDialogue;
use App\Dialogue\UserDialogueInteraction;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Auth\AuthManager as Auth;
use stdClass;

class DialogueInteractionController extends Controller
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

    public function create(Request $request)
    {
        if($uuid = $request->input('uuid')) {
            $userId = User::where('uuid', $request->input('uuid'))->first()->id;
        } else {
            $userId = $this->auth->user()->id;
        }

        $interaction = UserDialogueInteraction::where('user_id', $userId)->where('user_dialogue_id', $request->input('name'))->first();

        if (!$interaction) {
            $interaction = new UserDialogueInteraction(
                [
                    'user_id' => $userId,
                    'user_dialogue_id' => UserDialogue::where('name', $request->input('name'))->first()->id,
                    'data' => $request->input('data'),
                    'duration' => null
                ]);
            $interaction->save();
        }
        return $interaction;
    }

    public function update(Request $request, $id)
    {
        $interaction = UserDialogueInteraction::find($id);
        $interaction->duration = $request->input('duration');
        if(Input::has("data")) $interaction->data = $request->input('data');
        $interaction->save();
        return $interaction;
    }
}

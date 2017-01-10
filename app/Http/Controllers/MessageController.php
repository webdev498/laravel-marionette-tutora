<?php namespace App\Http\Controllers;

use App\Toast;
use App\Tutor;
use Illuminate\Http\Request;
use App\Commands\SendMessageCommand;
use Illuminate\Auth\AuthManager as Auth;

class MessageController extends Controller
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * Create a new controller instance.
     *
     * @param  Auth $auth
     * @return void
     */
    public function __construct(
        Auth $auth
    ) {
        $this->auth = $auth;
    }

    /**
     * @return Response
     */
    public function redirect(Request $request)
    {
        $user = $this->auth->user();

        if ($request->uuid && $user) {
            return redirect()
                ->route(($user instanceof Tutor ? 'tutor' : 'student').'.messages.show', [
                    'uuid' => $request->uuid
                ]);
        }

        if ( ! $request->recipients) {
            return redirect()
                ->guest(route('auth.login'))
                ->with([
                    'toast' => new Toast('Please log in to view your messages.', Toast::ERROR),
                ]);
        }

        $message = $this->dispatchFromArray(SendMessageCommand::class, [
            'uuid'        => null,
            'to'          => $request->recipients ? explode(',', (string) $request->recipients) : null,
            'body'        => null,
            'from_system' => false,
        ]);

        $redirect = $user instanceof Tutor
                ? route('tutor.messages.show', ['uuid' => $message->uuid])
                : route('student.messages.show', ['uuid' => $message->uuid]);

        return redirect()->to($redirect);
    }

}

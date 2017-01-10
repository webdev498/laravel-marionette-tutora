<?php namespace App\Http\Controllers;

use App\Toast;
use App\Tutor;
use App\UserProfile;
use Illuminate\Http\Request;
use App\Commands\ContactUsCommand;
use Illuminate\Auth\AuthManager as Auth;

class AboutController extends Controller 
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return Response
     */
    public function index()
    {
        $user = $this->auth->user();

        if ($user instanceof Tutor) {
            $inactive = ! in_array($user->profile->status, [
                UserProfile::LIVE,
                UserProfile::OFFLINE
            ]);
        } else {
            $inactive = false;
        }

        return view('about.index', compact('inactive'));
    }

    public function contact(Request $request)
    {
        $this->dispatchFrom(ContactUsCommand::class, $request);

        return redirect()
            ->route('about.index')
            ->with([
                'toast' => new Toast('Your message has been sent!', Toast::SUCCESS),
            ]);
    }

}

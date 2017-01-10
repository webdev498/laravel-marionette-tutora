<?php namespace App\Http\Controllers;

use App\Toast;
use Illuminate\Http\Request;
use App\Commands\ResetPasswordCommand;
use App\Commands\RequestPasswordResetCommand;

class PasswordController extends Controller
{

    public function index()
    {
        return view('password.index');
    }

    public function create(Request $request)
    {
        $this->dispatchFrom(RequestPasswordResetCommand::class, $request);

        return redirect()
            ->route('home')
            ->with([
                'toast' => new Toast("An email with a password reset link has been mailed to you.", Toast::SUCCESS),
            ]);
    }

    public function edit($token)
    {
        return view('password.edit', [
            'token' => $token,
        ]);
    }

    public function update(Request $request)
    {
        $this->dispatchFrom(ResetPasswordCommand::class, $request);

        return redirect()
            ->route('auth.login')
            ->with([
                'toast' => new Toast('Your password has been reset. You can now login using your new password.', Toast::SUCCESS),
            ]);
    }

}

<?php

namespace App\Http\ViewComposers;

use App\Commands\FindUserUnreadMessagesCountCommand;
use Illuminate\Auth\Guard as Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Route;

class StudentNavigationComposer
{
    use DispatchesCommands;

    /**
     * Create an instance of this
     *
     * @param  Auth    $auth
     * @return void
     */
    public function __construct(
        Auth       $auth  
    ) {
        $this->auth       = $auth;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return view
     */
    public function compose(View $view)
    {
        $user = $this->auth->user();

        $unreadMessages = $this->dispatch(new FindUserUnreadMessagesCountCommand($user));

        return $view->with(['unreadMessages' => $unreadMessages]);
    }
}

<?php

namespace App\Http\ViewComposers;

use App\Address;
use App\Commands\Jobs\FindTutorJobsCountCommand;
use App\Commands\FindUserUnreadMessagesCountCommand;
use Illuminate\Auth\Guard as Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Route;

class TutorNavigationComposer
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

        $hasAddress  = $user->addresses()->where('name', Address::NORMAL)->whereNotNull('latitude')->count() > 0;
        
        if(!$hasAddress) {
            return;
        }

        $jobCount = $this->dispatch(new FindTutorJobsCountCommand($user));

        $unreadMessages = $this->dispatch(new FindUserUnreadMessagesCountCommand($user));

        return $view->with(['jobCount' => $jobCount, 'unreadMessages' => $unreadMessages]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Commands\Subscriptions\UnsubscribeUserCommand;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    

    public function unsubscribeFromUrl(Request $request)
    {
    	$unsubscribed = $this->dispatchFrom(UnsubscribeUserCommand::class, $request);

    	return view('subscriptions.unsubscribe', compact('unsubscribed'));

    }
}

<?php namespace App\Http\Controllers;

class PolicyController extends Controller 
{

    /**
     * @return Response
     */
    public function privacy()
    {
        return view('policy.privacy');
    }

}

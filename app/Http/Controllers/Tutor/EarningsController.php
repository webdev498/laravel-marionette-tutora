<?php namespace App\Http\Controllers\Tutor;

class EarningsController extends TutorController
{

    /**
     * Show the messages to the user
     *
     * @return Response
     */
    public function index()
    {
        return view('tutor.earnings.index');
    }

}

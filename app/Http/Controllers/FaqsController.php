<?php namespace App\Http\Controllers;

class FaqsController extends Controller 
{

    /**
     * @return Response
     */
    public function student()
    {
        return view('faqs.student');
    }

    public function tutor()
    {
        return view('faqs.tutor');
    }

}

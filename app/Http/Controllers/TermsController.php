<?php namespace App\Http\Controllers;

class TermsController extends Controller 
{

    /**
     * @return Response
     */
    public function index()
    {
        return view('terms.index');
    }

}

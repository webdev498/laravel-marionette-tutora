<?php namespace App\Http\Controllers;

use App\Search\Algorithm\TutorProfileScorer;
use App\Students\StudentStatusCalculator;


class HomeController extends Controller 
{

    /**
     * @return Response
     */
    public function index()
    {
        
        return view('home', [
            'canonical'=> route('home'),
        ]);
        
    }

    public function test(TutorProfileScorer $scorer)
    {     
        
    }

}

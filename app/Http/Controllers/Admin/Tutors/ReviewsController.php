<?php

namespace App\Http\Controllers\Admin\Tutors;

use App\Presenters\TutorPresenter;
use App\Tutor;
use Illuminate\Http\Request;
use App\Repositories\Contracts\TutorRepositoryInterface;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserReviewRepositoryInterface;


class ReviewsController extends Controller
{
    /**
     * @var TutorRepositoryInterface
     */
    protected $tutors;

    /**
     * @var UserReviewRepositoryInterface
     */
    protected $reviews;

    /**
     * Create an instance of the controller
     *
     * @param  TutorRepositoryInterface         $tutors
     * @param  UserReviewRepositoryInterface    $reviews
     */
    public function __construct(
        TutorRepositoryInterface         $tutors,
        UserReviewRepositoryInterface    $reviews
    ) {
        $this->tutors    = $tutors;
        $this->reviews  = $reviews;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $uuid)
    {
        //
        $tutor = $this->tutors->findByUuid($uuid);
        $status = $request->get('status', 'active');

        $tutor = $this->presentItem($tutor, new TutorPresenter(), [
            'include' => [
                'reviews'
            ]
        ]);

        return view('admin.tutors.reviews.index', compact('tutor', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return 'hallo';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

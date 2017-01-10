<?php

namespace App\Http\Controllers\Tutor;

use App\Tutor;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Presenters\StudentPresenter;
use App\Presenters\JobPresenter;
use App\Presenters\SearchTutorJobsPresenter;
use Illuminate\Auth\AuthManager as Auth;
use App\Commands\Jobs\FindTutorJobsCommand;
use App\Auth\Exceptions\UnauthorizedException;
use App\Repositories\Contracts\TutorRepositoryInterface;
use App\Repositories\Contracts\JobRepositoryInterface;

class JobsController extends Controller
{
    /**
     * @var TutorRepositoryInterface
     */
    protected $tutors;

    /**
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * Create an instance of the controller
     *
     * @param  TutorRepositoryInterface $tutors
     * @param  JobRepositoryInterface   $jobs
     * @param  Auth                     $auth
     */
    public function __construct(
        TutorRepositoryInterface $tutors,
        JobRepositoryInterface   $jobs,
        Auth                     $auth
    ) {
        $this->tutors = $tutors;
        $this->jobs   = $jobs;
        $this->auth   = $auth;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws UnauthorizedException
     */
    public function index(Request $request)
    {
        $tutor = $this->auth->user();


        if (!$tutor->isTutor()) {
            throw new UnauthorizedException();
        }

        $results = $this->dispatchFrom(FindTutorJobsCommand::class, $request, [
            'tutor' => $tutor,
        ]);

        // dd($results);

        $_results = $this->presentItem($results, new SearchTutorJobsPresenter());

        $preload = [
            'jobs' => $_results->jobs->toArrayRecursively(),
        ];

        // Return
        return view ('tutor.jobs.index', [
            'results' => $_results,
            'preload' => $preload,
        ]);
    }



    /**
     * Display an individual job.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws UnauthorizedException
     */
    public function show(Request $request)
    {
        $tutor = $this->auth->user();

        if (!$tutor->isTutor()) {
            throw new UnauthorizedException();
        }

        // Return
        return view ('tutor.jobs.show');
    }
}

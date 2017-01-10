<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Presenters\StudentPresenter;
use App\Presenters\JobPresenter;
use App\Pagination\JobsPaginator;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\JobRepositoryInterface;

class JobsController extends Controller
{
    /**
     * @var StudentRepositoryInterface
     */
    protected $students;

    /**
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * @var JobsPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  StudentRepositoryInterface $students
     * @param  JobRepositoryInterface     $jobs
     * @param  JobsPaginator              $paginator
     */
    public function __construct(
        StudentRepositoryInterface $students,
        JobRepositoryInterface     $jobs,
        JobsPaginator              $paginator
    ) {
        $this->students  = $students;
        $this->jobs      = $jobs;
        $this->paginator = $paginator;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Options
        $page    = (integer) $request->get('page', 1);
        $filter  = (integer) $request->get('filter');
        $perPage = JobsPaginator::PER_PAGE;

        // Lookup
        $items = $this->jobs
            ->with([
                'locations',
                'subject',
                'user',
                'messageLines'
            ])
            ->paginate($page, $perPage)
            ->get($filter);

        $count = $this->jobs->count($filter);

        // Paginate
        $jobs = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('admin.jobs.index'),
        ]);

        // Present
        $jobs = $this->presentCollection(
            $jobs,
            new JobPresenter(),
            [
                'include' => [
                    'location',
                    'subject',
                    'student',
                    'replies'
                ],
                'meta' => [
                    'count'      => $jobs->count(),
                    'total'      => $count,
                    'pagination' => $jobs
                                    ->appends(compact('filter'))
                                    ->render(),
                ],
            ]
        );

        // Return
        return view ('admin.jobs.index', compact('jobs', 'filter'));
    }
}

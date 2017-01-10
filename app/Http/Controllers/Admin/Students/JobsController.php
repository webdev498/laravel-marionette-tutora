<?php

namespace App\Http\Controllers\Admin\Students;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Presenters\StudentPresenter;
use App\Presenters\JobPresenter;
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
     * Create an instance of the controller
     *
     * @param  StudentRepositoryInterface $students
     * @param  JobRepositoryInterface     $jobs
     */
    public function __construct(
        StudentRepositoryInterface $students,
        JobRepositoryInterface     $jobs
    ) {
        $this->students = $students;
        $this->jobs     = $jobs;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string $uuid
     *
     * @return \Illuminate\Http\Response
     */
    public function index($uuid)
    {
        // Lookup
        $student = $this->students->findByUuid($uuid);

        // Abort
        if ( ! $student) {
            abort(404);
        }

        // Jobs
        $jobs = $this->jobs
            ->with([
                'locations',
                'subject',
            ])
            ->getByStudent($student, 1, 25);

        // Present
        $student       = $this->presentItem($student, new StudentPresenter());
        $jobs = $this->presentCollection(
            $jobs,
            new JobPresenter(),
            [
                'include' => [
                    'location',
                    'subject',
                ]
            ]
        );

        // Return
        return view ('admin.students.jobs.index', compact('student', 'jobs'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Presenters\StudentPresenter;
use App\Pagination\StudentsPaginator;
use App\Repositories\Contracts\StudentRepositoryInterface;

class StudentsController extends Controller
{
    /**
     * @var StudentRepositoryInterface
     */
    protected $students;

    /**
     * @var StudentPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  StudentRepositoryInterface $students
     * @param  StudentsPaginator          $paginator
     * @return void
     */
    public function __construct(
        StudentRepositoryInterface $students,
        StudentsPaginator          $paginator
    ) {
        $this->students  = $students;
        $this->paginator = $paginator;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // Options
        $page   = (integer) $request->get('page', 1);
        $filter = (string)  $request->get('filter');
        // Lookups
        $items = $this->students
            ->with([
                'tasks:next',
            ])
            ->paginate(
                $page,
                StudentsPaginator::PER_PAGE
            )
            ->{$filter ? 'getByTaskCategory' : 'getByTask'}(
                $filter
            );
        $count = $this->students->{$filter ? 'countByTaskCategory' : 'count'}($filter);
        // Paginate
        $students = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('admin.students.index'),
        ]);
        // Present
        $students = $this->presentCollection(
            $students,
            new StudentPresenter(),
            [
                'include' => [
                    'tasks',
                ],
                'meta' => [
                    'count'      => $students->count(),
                    'total'      => $count,
                    'pagination' => $students
                        ->appends(compact('filter'))
                        ->render(),
                ],
            ]
        );
        // Return
        return view('admin.students.index', compact('students', 'filter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $uuid
     * @return Response
     */
    public function show($uuid)
    {
        return redirect()
            ->route('admin.students.personal.show', compact('uuid'));
    }
}

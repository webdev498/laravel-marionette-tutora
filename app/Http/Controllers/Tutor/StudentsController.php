<?php

namespace App\Http\Controllers\Tutor;

use Illuminate\Http\Request;
use App\Presenters\StudentPresenter;
use App\Pagination\StudentsPaginator;
use Illuminate\Auth\AuthManager as Auth;
use App\Repositories\Contracts\StudentRepositoryInterface;

class StudentsController extends TutorController
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * StudentRepositoryInterface
     */
    protected $students;

    /**
     * StudentsPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  Auth                       $auth
     * @param  StudentRepositoryInterface $students
     * @param  StudentsPaginator          $paginator
     * @return void
     */
    public function __construct(
        Auth                       $auth,
        StudentRepositoryInterface $students,
        StudentsPaginator          $paginator
    ) {
        $this->auth      = $auth;
        $this->students  = $students;
        $this->paginator = $paginator;
    }

    /**
     * Show the students to the user
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // Attributes
        $page    = (int) $request->get('page', 1);
        $perPage = StudentsPaginator::PER_PAGE;
        // Lookups
        $tutor = $this->auth->user();
        $count = $this->students->countByTutor($tutor);
        $items = $this->students
            ->with([
                'relationships',
                'relationships.message',
                'relationships.lessons:next',
                'relationships.lessons.subject',
                'relationships.lessons.bookings:next',
            ])
            ->getByTutor($tutor, $page, $perPage);
        // Paginate
        $students = $this->paginator->paginate($items, $count, $page);
        // Present
        $students = $this->presentCollection(
            $students,
            new StudentPresenter(),
            [
                'include' => [
                    'relationships',
                    'relationships.message',
                    'relationships.lessons',
                    'relationships.lessons.subject',
                    'relationships.lessons.bookings',
                ],
                'meta' => [
                    'count'      => $students->count(),
                    'total'      => $count,
                    'pagination' => $students->render(),
                ],
            ]
        );
        // Return
        return view('tutor.students.index', compact('students'));
    }

}

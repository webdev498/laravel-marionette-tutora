<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Presenters\TutorPresenter;
use App\Pagination\TutorsPaginator;
use Illuminate\Auth\AuthManager as Auth;
use App\Repositories\Contracts\TutorRepositoryInterface;

class TutorsController extends StudentController
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * TutorRepositoryInterface
     */
    protected $tutors;

    /**
     * TutorsPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  Auth                     $auth
     * @param  TutorRepositoryInterface $tutors
     * @param  TutorsPaginator          $paginator
     * @return void
     */
    public function __construct(
        Auth                     $auth,
        TutorRepositoryInterface $tutors,
        TutorsPaginator          $paginator
    ) {
        $this->auth      = $auth;
        $this->tutors    = $tutors;
        $this->paginator = $paginator;
    }

    /**
     * Show the tutors to the user
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // Attributes
        $page    = (int) $request->get('page', 1);
        $perPage = TutorsPaginator::PER_PAGE;
        // Lookups
        $student = $this->auth->user();
        $count   = $this->tutors->countByStudent($student);
        $items   = $this->tutors
            ->with([
                "reviews:by({$student->id})",
                'relationships',
                'relationships.message',
                'relationships.lessons:next',
                'relationships.lessons.subject',
                'relationships.lessons.bookings:next',
            ])
            ->getByStudent($student, $page, $perPage);
        // Paginate
        $tutors = $this->paginator->paginate($items, $count, $page);
        // Present
        $tutors = $this->presentCollection(
            $tutors,
            new TutorPresenter(),
            [
                'include' => [
                    'actions',
                    'relationships',
                    'relationships.message',
                    'relationships.lessons',
                    'relationships.lessons.subject',
                    'relationships.lessons.bookings',
                ],
                'meta' => [
                    'count'      => $tutors->count(),
                    'total'      => $count,
                    'pagination' => $tutors->render(),
                ],
            ]
        );
        // Return
        return view('student.tutors.index', compact('tutors'));
    }

}

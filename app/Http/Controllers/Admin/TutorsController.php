<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Presenters\TutorPresenter;
use App\Pagination\TutorsPaginator;
use App\Repositories\Contracts\TutorRepositoryInterface;

class TutorsController extends AdminController
{

    /**
     * @var TutorRepositoryInterface
     */
    protected $tutors;

    /**
     * @var TutorsPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  TutorRepositoryInterface $tutors
     * @param  TutorsPaginator          $paginator
     * @return void
     */
    public function __construct(
        TutorRepositoryInterface $tutors,
        TutorsPaginator          $paginator
    ) {
        $this->tutors    = $tutors;
        $this->paginator = $paginator;
    }

    /**
     * Show a list of tutor/student tutors
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
        $items = $this->tutors
            ->with([
                'profile',
                'tasks',
                'backgroundCheck',
                'identityDocument'
            ])
            ->paginate(
                $page,
                TutorsPaginator::PER_PAGE
            )
            ->{$filter ? 'getBy' . $filter : 'get'}();

        $count = $this->tutors
            ->{$filter ? 'countBy' . $filter : 'count'}();
        // Paginate

        $tutors = $this->paginator->paginate($items, $count, $page);
        // Present
        $tutors = $this->presentCollection(
            $tutors,
            new TutorPresenter(),
            [
                'include' => [
                    'profile',
                    $filter == 'Review' ? 'background_checks' : null,
                    'identity_document',
                    'tasks'
                ],
                'meta' => [
                    'count'      => $tutors->count(),
                    'total'      => $count,
                    'pagination' => $tutors
                        ->appends(compact('filter'))
                        ->render(),
                ],
            ]
        );
        // Return

        return view('admin.tutors.index', compact('tutors', 'filter'));
    }

    /**
     * Show a given tutor
     *
     * @param  string $uuid
     * @return Response
     */
    public function show($uuid)
    {
        return redirect()
            ->route('admin.tutors.personal.show', compact('uuid'));
    }
}

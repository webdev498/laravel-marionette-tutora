<?php

namespace App\Http\Controllers\Admin\Relationships;

use Illuminate\Http\Request;
use App\Presenters\RelationshipPresenter;
use App\Presenters\LessonBookingPresenter;
use App\Pagination\LessonBookingsPaginator;
use App\Http\Controllers\Admin\AdminController;
use App\Repositories\Contracts\RelationshipRepositoryInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class LessonsController extends AdminController
{
    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * @var LessonBookingsPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  RelationshipRepositoryInterface  $relationships
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  LessonBookingsPaginator          $paginator
     * @return void
     */
    public function __construct(
        RelationshipRepositoryInterface  $relationships,
        LessonBookingRepositoryInterface $bookings,
        LessonBookingsPaginator          $paginator

    ) {
        $this->relationships = $relationships;
        $this->bookings      = $bookings;
        $this->paginator     = $paginator;
    }

    /**
     * Display a list of lessons for the resource.
     *
     * @param  Request $request
     * @param  integer $id
     * @return Response
     */
    public function index(Request $request, $id)
    {
        // Options
        $page    = (int) $request->get('page', 1);
        $perPage = LessonBookingsPaginator::PER_PAGE;
        // Lookup relationship
        $relationship = $this->relationships->findByIdOrFail($id);
        // Lookup bookings
        $items = $this->bookings
            ->paginate($page, $perPage)
            ->with([
                'lesson',
                'lesson.relationship'
            ])
            ->getByRelationship($relationship);
        $count = $this->bookings->countByRelationship($relationship);
        // Paginate bookings
        $bookings = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('admin.relationships.lessons.index', compact('id'))
        ]);
        // Present
        $relationship = $this->presentItem(
            $relationship,
            new RelationshipPresenter(),
            [
                'include' => [
                    'tutor',
                    'student',
                ],
            ]
        );
        $bookings = $this->presentCollection(
            $bookings,
            new LessonBookingPresenter(),
            [
                'include' => [
                    'lesson',
                    'lesson.subject',
                    'lesson.relationship',
                    'lesson.relationship.tutor',
                    'lesson.relationship.student',
                ],
                'meta' => [
                    'count'      => $bookings->count(),
                    'total'      => $count,
                    'pagination' => $bookings->render(),
                ],
            ]
        );
        // Return
        return view('admin.relationships.lessons.index', compact('relationship', 'bookings'));
    }

}

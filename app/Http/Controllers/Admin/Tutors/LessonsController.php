<?php

namespace App\Http\Controllers\Admin\Tutors;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Presenters\TutorPresenter;
use App\Http\Controllers\Controller;
use App\Presenters\LessonBookingPresenter;
use App\Pagination\LessonBookingsPaginator;
use App\Repositories\Contracts\TutorRepositoryInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class LessonsController extends Controller
{
    /**
     * @var TutorRepositoryInterface
     */
    protected $tutors;

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
     * @param  TutorRepositoryInterface         $tutors
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  LessonBookingsPaginator          $paginator
     */
    public function __construct(
        TutorRepositoryInterface         $tutors,
        LessonBookingRepositoryInterface $bookings,
        LessonBookingsPaginator          $paginator
    ) {
        $this->tutors    = $tutors;
        $this->bookings  = $bookings;
        $this->paginator = $paginator;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $uuid)
    {
        // Options
        $page    = (int) $request->get('page', 1);
        $perPage = LessonBookingsPaginator::PER_PAGE;
        $status = $request->get('status', 'upcoming');
        // Lookup tutor
        $tutor = $this->tutors->findByUuid($uuid);
        // Abort
        if ( ! $tutor) {
            abort(404);
        }
        // Lookup bookings
        $items = $this->bookings->getByTutorByStatus($tutor, $status, $page, $perPage);
        $count = $this->bookings->countByTutorByStatus($tutor, $status);
        // Paginate
        $bookings = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('admin.tutors.lessons.index', ['uuid' => $tutor->uuid])
        ]);
        // Present
        $tutor    = $this->presentItem($tutor, new TutorPresenter());
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
                    'pagination' => $bookings
                        ->appends(compact('status'))
                        ->render(),
                ],
            ]
        );
        // Return
        return view('admin.tutors.lessons.index', compact('tutor', 'bookings', 'status'));
    }
}

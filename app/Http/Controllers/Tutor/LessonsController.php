<?php

namespace App\Http\Controllers\Tutor;

use App\LessonBooking;
use Illuminate\Http\Request;
use App\Presenters\LessonBookingPresenter;
use App\Pagination\LessonBookingsPaginator;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class LessonsController extends TutorController
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * @var LessonBookingsPaginator
     */
    protected $paginator;

    /**
     * Create an instance of this
     *
     * @param  Auth                             $auth
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  LessonBookingsPaginator          $paginator
     * @return void
     */
    public function __construct(
        Auth                             $auth,
        LessonBookingRepositoryInterface $bookings,
        LessonBookingsPaginator          $paginator
    ) {
        $this->auth      = $auth;
        $this->bookings  = $bookings;
        $this->paginator = $paginator;
    }

    /**
     * Show the lessons to the tutor
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // Options
        $page    = (int) $request->get('page', 1);
        $perPage = LessonBookingsPaginator::PER_PAGE;
        $status    = $request->get('status', 'upcoming');
        // Lookups
        $tutor = $this->auth->user();
        $items = $this->bookings->getByTutorByStatus($tutor, $status, $page, $perPage);
        $count = $this->bookings->countByTutorByStatus($tutor, $status);
        // Paginate
        $bookings = $this->paginator->paginate($items, $count, $page);
        // Present
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
        return view('tutor.lessons.index', compact('bookings', 'status'));
    }

}

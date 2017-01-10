<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Commands\RefundLessonBookingCommand;
use App\Presenters\LessonBookingPresenter;
use App\Pagination\LessonBookingsPaginator;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class LessonsController extends Controller
{
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
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  LessonBookingsPaginator          $paginator
     * @return void
     */
    public function __construct(
        LessonBookingRepositoryInterface $bookings,
        LessonBookingsPaginator          $paginator
    ) {
        $this->bookings  = $bookings;
        $this->paginator = $paginator;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Options
        $page         = (int) $request->get('page', 1);
        $perPage      = LessonBookingsPaginator::PER_PAGE;
        $status       = (string) $request->get('status');
        $chargeStatus = (string) $request->get('chargeStatus');
        // Lookup bookings
        $items = $this->bookings
            ->paginate($page, $perPage)
            ->getByStatus($status, $chargeStatus);
        $count = $this->bookings->countByStatus($status, $chargeStatus);
        // Paginate
        $bookings = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('admin.lessons.index')
        ]);
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
                        ->appends(compact('status', 'chargeStatus'))
                        ->render(),
                ],
            ]
        );
        
        // Return
        return view('admin.lessons.index', compact('bookings', 'status', 'chargeStatus'));
    }

    // Not used???

    // public function refund($uuid)
    // {
    //     $booking = $this->bookings->findByUuid($uuid);
    //     loginfo('refunding lesson');
    //     $refund = $this->dispatch(new RefundLessonBookingCommand($booking));
    //     return back();
    // }
}

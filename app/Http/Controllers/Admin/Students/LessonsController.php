<?php

namespace App\Http\Controllers\Admin\Students;

use App\Commands\ConfirmLessonBookingCommand;
use App\Commands\RetryLessonBookingCommand;
use App\Http\Controllers\Controller;
use App\Pagination\LessonBookingsPaginator;
use App\Presenters\LessonBookingPresenter;
use App\Presenters\StudentPresenter;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Http\Request;

class LessonsController extends Controller
{
    /**
     * @var StudentRepositoryInterface
     */
    protected $students;

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
     * @param  StudentRepositoryInterface       $students
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  LessonBookingsPaginator          $paginator
     * @return void
     */
    public function __construct(
        StudentRepositoryInterface       $students,
        LessonBookingRepositoryInterface $bookings,
        LessonBookingsPaginator          $paginator
    ) {
        $this->students  = $students;
        $this->bookings  = $bookings;
        $this->paginator = $paginator;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @param  string  $uuid
     * @return Response
     */
    public function index(Request $request, $uuid)
    {
        // Options
        $page    = (int) $request->get('page', 1);
        $perPage = LessonBookingsPaginator::PER_PAGE;
        $status = $request->get('status', 'upcoming');
        // Lookup
        $student = $this->students->findByUuid($uuid);
        // Abort
        if ( ! $student) {
            abort(404);
        }
        // Lookup
        $items = $this->bookings->getByStudentByStatus($student, $status, $page, $perPage);
        $count = $this->bookings->countByStudentByStatus($student, $status);
        // Paginate
        $bookings = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('admin.students.lessons.index', compact('uuid'))
        ]);
        // Present
        $student  = $this->presentItem($student, new StudentPresenter());
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
        return view('admin.students.lessons.index', compact('student', 'bookings', 'status'));
    }

    public function retry(Request $request, $uuid, $lessonUuid)
    {
        $booking = $this->dispatch(new RetryLessonBookingCommand($lessonUuid));

        return redirect()
            ->back();
    }

    public function confirm(Request $request, $uuid, $lessonUuid)
    {
        $booking = $this->dispatch(new ConfirmLessonBookingCommand($lessonUuid));

        return redirect()
            ->back();
    }
}

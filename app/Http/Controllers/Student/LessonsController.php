<?php

namespace App\Http\Controllers\Student;

use App\Toast;
use App\LessonBooking;
use Illuminate\Http\Request;
use App\Presenters\LessonPresenter;
use App\Presenters\LessonBookingPresenter;
use App\Pagination\LessonBookingsPaginator;
use App\Presenters\StudentPresenter;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Transformers\LessonBookingTransformer;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class LessonsController extends StudentController
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var LessonRepositoryInterface
     */
    protected $lessons;

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
     * @param  LessonRepositoryInterface        $lessons
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  LessonBookingsPaginator          $paginator
     * @return void
     */
    public function __construct(
        Auth                             $auth,
        LessonRepositoryInterface        $lessons,
        LessonBookingRepositoryInterface $bookings,
        LessonBookingsPaginator          $paginator
    ) {
        $this->auth      = $auth;
        $this->lessons   = $lessons;
        $this->bookings  = $bookings;
        $this->paginator = $paginator;
    }

    /**
     * Show the lesson bookings to the user
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // Options
        $page    = (int) $request->get('page', 1);
        $status = $request->get('status', 'upcoming');
        $perPage = LessonBookingsPaginator::PER_PAGE;

        // Lookups
        $user  = $this->auth->user();
        $items = $this->bookings->getByStudentByStatus($user, $status, $page, $perPage);
        $count = $this->bookings->countByStudentByStatus($user, $status);
        // Paginate
        $bookings = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('student.lessons.index')
        ]);
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
        return view('student.lessons.index', compact('bookings', 'status'));
    }

    /**
     * Confirm the relationship by a given booking uuid.
     *
     * @param  string $uuid
     * @return Response
     */
    public function confirm($uuid)
    {
        // Lookups
        $user    = $this->auth->user();
        $booking = $this->bookings->findByUuid($uuid);
        // 404
        if ( ! $booking) {
            return abort(404);
        }
        // More lookups
        $lesson       = $booking->lesson;
        $relationship = $lesson->relationship;
        $student      = $relationship->student;
        // 401
        if ($student->id !== $user->id) {
            return abort(401);
        }
        // Preload
        $preload = [
            'booking' => $this->transformItem(
                $booking,
                new LessonBookingTransformer()
            ),
        ];
        // Present
        $booking = $this->presentItem(
            $booking,
            new LessonBookingPresenter(),
            [
                'include' => [
                    'lesson',
                    'lesson.subject',
                    'lesson.schedule',
                    'lesson.relationship',
                    'lesson.relationship.tutor',
                    'lesson.relationship.student',
                ],
            ]
        );

        // Present
        $student = $this->presentItem(
            $student,
            new StudentPresenter(),
            [
                'include' => [
                    'addresses',
                ],
            ]
        );

        // Return
        return view('student.lessons.confirm', compact('booking', 'preload', 'student'));
    }

    public function confirmed($id)
    {


        return view('student.lessons.confirmed');
    }

}

<?php namespace App\Http\Controllers\Student;

use App\Presenters\MessagePresenter;
use App\Presenters\LessonBookingPresenter;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class DashboardController extends StudentController
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var LessonBookingRepository
     */
    protected $bookings;

    /**
     * @var MessageRepositoryInterface
     */
    protected $messages;

    /**
     * Create an instance of this
     *
     * @param  Auth                             $auth
     * @param  LessonBookingRepositoryInterface $bookings
     * @param  MessageRepositoryInterface       $messages
     * @return void
     */
    public function __construct(
        Auth                             $auth,
        LessonBookingRepositoryInterface $bookings,
        MessageRepositoryInterface       $messages
    ) {
        $this->auth     = $auth;
        $this->bookings = $bookings;
        $this->messages = $messages;
    }

    /**
     * Show the application dashboard to the user
     *
     * @return Response
     */
    public function index()
    {
        // Lookups
        $student  = $this->auth->user();
        $bookings = $this->bookings->getByStudent($student, 1, 5);
        $messages = $this->messages->getByUser($student, 1, 5);
        // Present
        $bookings = $this->presentCollection(
            $bookings,
            new LessonBookingPresenter(),
            [
                'meta' => [
                    'count' => $bookings->count(),
                ],
                'include' => [
                    'lesson',
                    'lesson.subject',
                    'lesson.relationship',
                    'lesson.relationship.tutor',
                ]
            ]
        );
        $messages = $this->presentCollection(
            $messages,
            new MessagePresenter(),
            [
                'meta' => [
                    'count' => $messages->count(),
                ],
                'include' => [
                    'lines',
                ],
            ]
        );
        // Return
        return view('student.dashboard.index', compact('bookings', 'messages'));
    }

}

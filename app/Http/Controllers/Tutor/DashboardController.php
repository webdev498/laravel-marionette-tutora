<?php namespace App\Http\Controllers\Tutor;

use App\Presenters\MessagePresenter;
use App\Presenters\LessonBookingPresenter;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class DashboardController extends TutorController
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
    public function index($dialogue = null)
    {
        $tutor    = $this->auth->user();
        $bookings = $this->bookings->getByTutor($tutor, 1, 5);
        $messages = $this->messages->getByUser($tutor, 1, 5);
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
                    'lesson.relationship.student',
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
        return view('tutor.dashboard.index', compact('bookings', 'messages'));
    }

}

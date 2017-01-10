<?php namespace App\Http\Controllers\Api;

use App\User;
use App\LessonBooking;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use App\Exceptions\AppException;
use App\Commands\EditLessonBookingCommand;
use App\Billing\Exceptions\BillingException;
use App\Commands\CreateLessonBookingCommand;
use App\Commands\CancelLessonBookingCommand;
use App\Commands\RefundLessonBookingCommand;
use App\Commands\ConfirmLessonBookingCommand;
use App\Commands\UpdateUserCommand;
use App\Auth\Exceptions\UnauthorizedException;
use App\Transformers\LessonBookingTransformer;
use App\Validators\Exceptions\ValidationException;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class BookingsController extends ApiController
{

    /**
     * @var AuthManager
     */
    protected $auth;

    /**
     * @var LessonBookingRepositoryInstance
     */
    protected $bookings;

    /**
     * Create a the controller.
     *
     * @param  AuthManager                     $auth
     * @param  LessonBookingRepositoryInstance $bookings
     * @return void
     */
    public function __construct(
        AuthManager                      $auth,
        LessonBookingRepositoryInterface $bookings
    ) {
        $this->auth     = $auth;
        $this->bookings = $bookings;
    }

    /**
     * Store the booking.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $booking = $this->dispatchFrom(CreateLessonBookingCommand::class, $request);

            return $this->respondWithItem($booking, new LessonBookingTransformer());
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

    /**
     * Show a booking.
     *
     * @param  string $uuid
     * @return Response
     */
    public function show($uuid)
    {
        try {
            $user    = $this->auth->user();
            $booking = $this->bookings
                ->with([
                    'lesson',
                    'lesson.relationship',
                    'lesson.relationship.tutor',
                    'lesson.relationship.student',
                ])
                ->findByUuid($uuid);

            $this->guardAgainstUnauthorizedAccess($user, $booking);

            return $this->respondWithItem($booking, new LessonBookingTransformer(), [
                'include' => [
                    'student.private',
                ],
            ]);
        } catch (ResourceNotFoundException $e) {
            return $this->respondWithNotFound('Not Found');
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

    /**
     * Edit a booking.
     *
     * @param  string $uuid
     * @return Response
     */
    public function edit(Request $request, $uuid)
    {
        try {
            $booking = $this->dispatchFrom(EditLessonBookingCommand::class, $request, [
                'uuid' => $uuid,
            ]);

            return $this->respondWithItem($booking, new LessonBookingTransformer());
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (ResourceNotFoundException $e) {
            return $this->respondWithNotFound('Not Found');
        }
    }

    /**
     * Cancel a booking.
     *
     * @param  Request $request
     * @param  string  $uuid
     * @return Response
     */
    public function cancel(Request $request, $uuid)
    {
        try {
            $booking = $this->dispatchFrom(CancelLessonBookingCommand::class, $request);

            return $this->transformItem($booking, new LessonBookingTransformer());
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (ResourceNotFoundException $e) {
            return $this->respondWithNotFound('Not Found');
        }
    }

    /**
     * Refund a booking.
     *
     * @param  Request $request
     * @param  string  $uuid
     * @return Response
     */
    public function refund(Request $request, $uuid)
    {
        try {
            $booking = $this->dispatchFrom(RefundLessonBookingCommand::class, $request);

            return $this->transformItem($booking, new LessonBookingTransformer());
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (ResourceNotFoundException $e) {
            return $this->respondWithNotFound('Not Found');
        }
    }

    /**
     * Confirm a booking.
     *
     * @param  Request $request
     * @param  string  $uuid
     * @return Response
     */
    public function confirm(Request $request, $uuid)
    {

        try {
            $booking = $this->dispatchFrom(ConfirmLessonBookingCommand::class, $request);

            $request->replace([
                'addresses' => $request->get('addresses'),
                'card'      => null
            ]);
            $student = $booking->student;
            $this->dispatchFrom(UpdateUserCommand::class, $request, ['uuid' => $student->uuid]);

            return $this->transformItem($booking, new LessonBookingTransformer(), [
                'meta' => [
                    'redirect' => route('student.lessons.confirmed', [
                        'booking' => $booking->uuid
                    ]),
                ],
            ]);
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (ResourceNotFoundException $e) {
            return $this->respondWithNotFound('Not Found');
        } catch (AppException $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    protected function guardAgainstUnauthorizedAccess(
        User $user = null,
        LessonBooking $booking = null
    ) {
        if ( ! $user || ! $booking) {
            throw new ResourceNotFoundException();
        }

        $tutor   = $booking->lesson->relationship->tutor;
        $student = $booking->lesson->relationship->student;

        if (
            ! $user->isAdmin()         &&
            $user->id !== $tutor->id   &&
            $user->id !== $student->id
        ) {
            throw new UnauthorizedException();
        }
    }

}

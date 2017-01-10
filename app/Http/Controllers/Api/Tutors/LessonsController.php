<?php namespace App\Http\Controllers\Api\Tutors;

use App\Http\Controllers\Api\ApiController;
use App\User;
use App\LessonBooking;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use App\Exceptions\AppException;
use App\Commands\EditLessonBookingCommand;
use App\Billing\Exceptions\BillingException;
use App\Commands\CreateLessonBookingForTutorCommand;
use App\Commands\CancelLessonBookingCommand;
use App\Commands\RefundLessonBookingCommand;
use App\Commands\ConfirmLessonBookingCommand;
use App\Auth\Exceptions\UnauthorizedException;
use App\Transformers\LessonBookingTransformer;
use App\Validators\Exceptions\ValidationException;
use App\Database\Exceptions\ResourceNotFoundException;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class LessonsController extends ApiController
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
     * @param  string $uuid
     * @param  Request $request
     *
     * @return Response
     */
    public function book(Request $request, $uuid)
    {
        try {
            $user    = $this->auth->user();
            $this->guardAgainstUnauthorizedAccess($user);

            $booking = $this->dispatchFrom(CreateLessonBookingForTutorCommand::class, $request, [
                'tutorId' => $uuid,
            ]);

            return $this->respondWithItem($booking, new LessonBookingTransformer());
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

    protected function guardAgainstUnauthorizedAccess(User $user = null) {
        if (!$user->isAdmin()) {
            throw new UnauthorizedException();
        }
    }

}

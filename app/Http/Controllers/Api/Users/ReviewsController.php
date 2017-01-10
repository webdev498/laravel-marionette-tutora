<?php namespace App\Http\Controllers\Api\Users;

use App\Commands\UpdateUserReviewCommand;
use App\Commands\GetUserReviewsCommand;
use App\Commands\DeleteUserReviewCommand;
use App\User;
use App\UserReview;
use Illuminate\Http\Request;
use App\Commands\CreateUserReviewCommand;
use App\Transformers\UserReviewTransformer;
use App\Http\Controllers\Api\ApiController;
use App\Auth\Exceptions\UnauthorizedException;
use App\Validators\Exceptions\ValidationException;
use Mockery\CountValidator\Exception;

class ReviewsController extends ApiController
{

    /**
     * Store the booking review.
     *
     * @param  Request $request
     * @return Response
     */
    public function create(Request $request, $uuid)
    {
        try {
            $review = $this->dispatchFrom(CreateUserReviewCommand::class, $request, [
                'uuid' => $uuid
            ]);

            return $this->respondWithItem($review, new UserReviewTransformer());
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

    public function index(Request $request, $uuid) {

        try {
            $reviews = $this->dispatchFrom(GetUserReviewsCommand::class, $request, [
                'uuid' => $uuid
            ]);

            return $this->transformCollection($reviews, new UserReviewTransformer());
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

    public function get(Request $request, $id) {

        $review = UserReview::find($id);

        try {
            return $this->respondWithItem($review, new UserReviewTransformer());
        } catch (Exception $e) {
            return $this->respondWithErrors('An error occured');
        }
    }

    public function post(Request $request, $id) {
        try {
            $review = $this->dispatchFrom(UpdateUserReviewCommand::class, $request, [
                'id' => $id
            ]);

            return $this->respondWithItem($review, new UserReviewTransformer());
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }

    public function delete(Request $request, $id) {
        try {
            $review = $this->dispatchFrom(DeleteUserReviewCommand::class, $request, [
                'id' => $id
            ]);

            return $this->respondWithItem($review, new UserReviewTransformer());
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }
}

<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{

    const CODE_WRONG_ARGS     = 'ERR_WRONG_ARGS';     // 400
    const CODE_BAD_REQUEST    = 'ERR_BAD_REQUEST';    // 400
    const CODE_UNAUTHORIZED   = 'ERR_UNAUTHORIZED';   // 401
    const CODE_FORBIDDEN      = 'ERR_FORBIDDEN';      // 403
    const CODE_NOT_FOUND      = 'ERR_NOT_FOUND';      // 404
    const CODE_CONFLICT       = 'ERR_CONFLICT';       // 409
    const CODE_INTERNAL_ERROR = 'ERR_INTERNAL_ERROR'; // 500

    protected function respondWithArray(Array $array, $status)
    {
        return new JsonResponse($array, $status);
    }

    /**
     * Transform an item, and create a json response for it
     *
     * @param  Mixed    $item
     * @param  Callable $callback
     * @param  Array    $options
     * @param  Integer  $status
     * @return JsonResponse
     */
    protected function respondWithItem($item, $callback, Array $options = [], $status = 200)
    {
        $data = $this->transformItem($item, $callback, $options);

        return $this->respondWithArray($data, $status);
    }

    protected function respondWithCreatedItem($item, $callback, Array $options = [])
    {
        return $this->respondWithItem($item, $callback, $options, 201);
    }

    protected function respondWithUpdatedItem($item, $callback, Array $options = [])
    {
        return $this->respondWithItem($item, $callback, $options, 200);
    }

    protected function respondWithErrors($message, Array $errors = null, $status = 400)
    {
        return $this->respondWithArray([
            'message'  => $message,
            'errors'   => $errors
        ], $status);
    }

    protected function respondWithBadRequest($message, Array $errors = null)
    {
        return $this->respondWithErrors($message, $errors, 400);
    }

    protected function respondWithUnauthorized($message, Array $errors = null)
    {
        return $this->respondWithErrors($message, $errors, 401);
    }

    protected function respondWithNotFound($message, Array $errors = null)
    {
        return $this->respondWithErrors($message, $errors, 404);
    }

    protected function respondWithConflict($message, Array $errors = null) {
        return $this->respondWithErrors($message, $errors, 409);
    }
}

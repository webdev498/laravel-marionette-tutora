<?php namespace App\Http\Controllers\Api;

use App\Auth\Exceptions\AuthException;
use App\Commands\Messages\FlagMessageLineCommand;
use App\Transformers\MessageLineTransformer;
use Illuminate\Auth\AuthManager as Auth;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Http\Request;

use App\Auth\Exceptions\UnauthorizedException;

class MessageLinesController extends ApiController
{

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * Create an instance of this
     *
     * @param  Database                 $database
     * @param  Auth                     $auth
     */
    public function __construct(
        Database $database,
        Auth $auth
    ) {
        $this->database = $database;
        $this->auth     = $auth;
    }

    /**
     * Flag message line request
     *
     * @param Request $request
     * @param string  $uuid
     *
     * @return mixed
     */
    public function flag(Request $request, $uuid)
    {
        try {
            return $this->database->transaction(function () use ($request, $uuid) {

                $messageLine = $this->dispatchFrom(FlagMessageLineCommand::class, $request, [
                    'uuid' => $uuid,
                ]);

                return $this->transformItem($messageLine, new MessageLineTransformer(), [
                    'include' => [],
                ]);
            });
        } catch (UnauthorizedException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        } catch (AuthException $e) {
            return $this->respondWithUnauthorized($e->getMessage());
        }
    }
}

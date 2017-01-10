<?php namespace App\Http\Controllers\Api\Users\Qualifications;

use Illuminate\Http\Request;
use App\Commands\QTSUpdateCommand;
use App\Http\Controllers\Api\ApiController;
use App\Validators\Exceptions\ValidationException;

class QTSController extends ApiController
{

    public function create(Request $request, $uuid)
    {
        try {
            return $this->dispatchFrom(QTSUpdateCommand::class, $request, [
                'uuid' => $uuid,
            ]);
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        }
    }

}

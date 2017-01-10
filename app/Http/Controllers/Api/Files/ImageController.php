<?php namespace App\Http\Controllers\Api\Files;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Commands\Files\ImageUploadCommand;
use App\Validators\Exceptions\ValidationException;
use App\Transformers\Files\ImageTransformer;

class ImageController extends ApiController
{

    public function create(Request $request)
    {
        try {
            $image = $this->dispatchFromArray(ImageUploadCommand::class, [
                'image' => $request->file('file'),
            ]);

            return $this->respondWithCreatedItem($image, new ImageTransformer());
        } catch (ValidationException $e) {
            return $this->respondWithBadRequest($e->getMessage(), $e->getErrors());
        }
    }

}

<?php namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Transformers\SubjectTransformer;

class QuizController extends ApiController
{

    /**
     * @return json
     */
    public function index()
    {
        $questions = config('quiz');

        return $questions;
    }

}

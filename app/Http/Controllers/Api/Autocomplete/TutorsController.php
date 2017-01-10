<?php

namespace App\Http\Controllers\Api\Autocomplete;

use App\Tutor;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Repositories\Contracts\TutorRepositoryInterface;

class TutorsController extends ApiController
{

    protected $tutors;

    public function __construct(TutorRepositoryInterface $tutors)
    {
        $this->tutors = $tutors;
    }

    public function index(Request $request)
    {
        // Options
        $query = $request->get('query', null);
        // 404
        if ( ! $query) {
            abort(400);
        }
        // Lookup
        $tutors = $this->tutors
            ->paginate(1, 25)
            ->getByQuery($query, ['first_name', 'last_name', 'uuid']);
        // Present
        $tutors = $this->transformCollection(
            $tutors,
            function (Tutor $tutor) {
                return [
                    'data'  => (string) $tutor->uuid,
                    'value' => sprintf(
                        '%s %s (%s)',
                        $tutor->first_name,
                        $tutor->last_name,
                        $tutor->uuid
                    ),
                ];
            }
        );
        // Return
        return $tutors;
    }

}

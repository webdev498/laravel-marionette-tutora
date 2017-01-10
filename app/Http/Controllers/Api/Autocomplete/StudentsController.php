<?php

namespace App\Http\Controllers\Api\Autocomplete;

use App\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Repositories\Contracts\StudentRepositoryInterface;

class StudentsController extends ApiController
{

    protected $students;

    public function __construct(StudentRepositoryInterface $students)
    {
        $this->students = $students;
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
        $students = $this->students
            ->paginate(1, 25)
            ->getByQuery($query, ['first_name', 'last_name', 'uuid']);
        // Present
        $students = $this->transformCollection(
            $students,
            function (Student $student) {
                return [
                    'data'  => (string) $student->uuid,
                    'value' => sprintf(
                        '%s %s (%s)',
                        $student->first_name,
                        $student->last_name,
                        $student->uuid
                    ),
                ];
            }
        );
        // Return
        return $students;
    }

}

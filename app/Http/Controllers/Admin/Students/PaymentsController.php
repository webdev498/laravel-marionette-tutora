<?php

namespace App\Http\Controllers\Admin\Students;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Commands\UpdateUserCommand;
use App\Presenters\StudentPresenter;
use App\Http\Controllers\Controller;
use App\Validators\Exceptions\ValidationException;
use App\Database\Exceptions\DuplicateResourceException;
use App\Repositories\Contracts\StudentRepositoryInterface;

class PaymentsController extends Controller
{
    /**
     * @var StudentRepositoryInterface
     */
    protected $students;

    /**
     * Create an instance of this controller
     *
     * @param  StudentRepositoryInterface $students
     * @return void
     */
    public function __construct(
        StudentRepositoryInterface $students
    ) {
        $this->students = $students;
    }

    /**
     * Show the specified resource.
     *
     * @param  string $uuid
     * @return Response
     */
    public function index($uuid)
    {
        // Lookup
        $student = $this->students->findByUuid($uuid);

        // Abort
        if ( ! $student) {
            abort(404);
        }
        // Present        
        $student = $this->presentItem(
            $student,
            new StudentPresenter(),
            [
                'include' => [
                    'addresses',
                    'tasks',
                    'note',
                    'searches',
                    'searches.subject'
                ],
            ]
        );
        // Return
        return view ('admin.students.payments.index', compact('student'));
    }
}

<?php

namespace App\Http\Controllers\Admin\Students;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Presenters\StudentPresenter;
use App\Presenters\RelationshipPresenter;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\RelationshipRepositoryInterface;

class RelationshipsController extends Controller
{
    /**
     * @var StudentRepositoryInterface
     */
    protected $students;

    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;

    /**
     * Create an instance of the controller
     *
     * @param  StudentRepositoryInterface      $students
     * @param  RelationshipRepositoryInterface $relationships
     * @return void
     */
    public function __construct(
        StudentRepositoryInterface      $students,
        RelationshipRepositoryInterface $relationships
    ) {
        $this->students      = $students;
        $this->relationships = $relationships;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string $uuid
     * @return \Illuminate\Http\Response
     */
    public function index($uuid)
    {
        // Lookup
        $student = $this->students->findByUuid($uuid);
        // Abort
        if ( ! $student) {
            abort(404);
        }
        // Relationships
        $relationships = $this->relationships
            ->with([
                'tutor',
                'message',
                'message.lines:last',
                'tasks:next',
            ]) 
            ->getByStudent($student, 1, 25);

        // Present
        $student       = $this->presentItem($student, new StudentPresenter());
        $relationships = $this->presentCollection(
            $relationships,
            new RelationshipPresenter(),
            [
                'include' => [
                    'tutor',
                    'message',
                    'tasks',
                ]
            ]
        );
        // Return
        return view ('admin.students.relationships.index', compact('student', 'relationships'));
    }
}

<?php

namespace App\Http\Controllers\Admin\Tutors;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Presenters\TutorPresenter;
use App\Presenters\RelationshipPresenter;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\TutorRepositoryInterface;
use App\Commands\UserRelationshipsCommand;

class RelationshipsController extends Controller
{
    /**
     * @var TutorRepositoryInterface
     */
    protected $tutors;

    /**
     * Create an instance of the controller
     *
     * @param  TutorRepositoryInterface $tutors
     */
    public function __construct(
        TutorRepositoryInterface $tutors
    ) {
        $this->tutors = $tutors;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string $uuid
     * @return \Illuminate\Http\Response
     */
    public function index($uuid)
    {
        list($user, $relationships) = $this->dispatchFromArray(UserRelationshipsCommand::class, [
            'uuid' => $uuid,
        ]);

        // Abort
        if ( !$user->isTutor()) {
            abort(404);
        }

        // Present
        $tutor = $this->presentItem(
            $user,
            new TutorPresenter()
        );
        $relationships = $this->presentCollection(
            $relationships,
            new RelationshipPresenter(),
            [
                'include' => [
                    'student',
                    'message',
                    'tasks',
                ],
            ]
        );

        // Return
        return view ('admin.tutors.relationships.index', compact('tutor', 'relationships'));
    }
}

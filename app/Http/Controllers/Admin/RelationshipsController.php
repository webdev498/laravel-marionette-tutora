<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Presenters\RelationshipPresenter;
use App\Commands\FormRelationshipCommand;
use App\Pagination\RelationshipsPaginator;
use App\Repositories\Contracts\RelationshipRepositoryInterface;

class RelationshipsController extends AdminController
{
    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;

    /**
     * @var RelationshipsPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  RelationshipRepositoryInterface $relationships
     * @param  RelationshipsPaginator          $paginator
     * @return void
     */
    public function __construct(
        RelationshipRepositoryInterface $relationships,
        RelationshipsPaginator          $paginator
    ) {
        $this->relationships = $relationships;
        $this->paginator     = $paginator;
    }

    /**
     * Show a list of tutor/student relationships
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // Options
        $page    = (integer) $request->get('page', 1);
        $filter  = (string)  $request->get('filter');
        $perPage = RelationshipsPaginator::PER_PAGE;
        // Lookup
        $items = $this->relationships
            ->paginate($page, $perPage)
            ->with([
                'tutor',
                'student',
                'message',
                'message.lines:last',
                'tasks',
            ])
            ->getOrderedByTasks($filter);
        $count = $this->relationships->count($filter);
        // Paginate
        $relationships = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('admin.relationships.index', compact('id'))
        ]);
        // Present
        $relationships = $this->presentCollection(
            $relationships,
            new RelationshipPresenter(),
            [
                'include' => [
                    'tutor',
                    'student',
                    'message',
                    'tasks',
                ],
                'meta' => [
                    'count'      => $relationships->count(),
                    'total'      => $count,
                    'pagination' => $relationships->render(),
                ],
            ]
        );
        // Return
        return view('admin.relationships.index', compact('relationships', 'filter'));
    }

    /**
     * Show the form to create a relationship
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.relationships.create');
    }

    /**
     * Store the new relationship.
     *
     * @param  Request $request
     * @return response
     */
    public function store(Request $request)
    {
        $relationship = $this->dispatchFrom(FormRelationshipCommand::class, $request);

        return redirect()
            ->route('admin.relationships.messages.show', [
                'id' => $relationship->id,
            ]);
    }

    /**
     * Show the relationship.
     *
     * @param  integer $id
     * @return Response
     */
    public function show($id)
    {
        // Return
        return redirect()
            ->route('admin.relationships.details.show', compact('id'));
    }
}

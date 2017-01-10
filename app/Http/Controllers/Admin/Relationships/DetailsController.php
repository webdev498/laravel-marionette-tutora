<?php

namespace App\Http\Controllers\Admin\Relationships;

use Illuminate\Http\Request;
use App\Presenters\RelationshipPresenter;
use App\Http\Controllers\Admin\AdminController;
use App\Repositories\Contracts\RelationshipRepositoryInterface;

class DetailsController extends AdminController
{

    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;

    /**
     * Create an instance of the controller
     *
     * @param  RelationshipRepositoryInterface $relationships
     * @return void
     */
    public function __construct(
        RelationshipRepositoryInterface $relationships
    ) {
        $this->relationships = $relationships;
    }

    /**
     * Show the relationship.
     *
     * @param  integer $id
     * @return Response
     */
    public function show($id)
    {
        // Lookup
        $relationship = $this->relationships->findById($id);
        // Present
        $relationship = $this->presentItem(
            $relationship,
            new RelationshipPresenter(),
            [
                'include' => [
                    'tutor',
                    'student',
                    'tasks',
                    'note',
                    'searches',
                    'searches.subject',
                ],
            ]
        );
        // Return
        return view('admin.relationships.details.show', compact('relationship'));
    }

    /**
     * Edit the relationship
     *
     * @param  integer $id
     * @return Response
     */
    public function edit($id)
    {
        // Lookup
        $relationship = $this->relationships->findById($id);
        // Present
        $relationship = $this->presentItem(
            $relationship,
            new RelationshipPresenter(),
            [
                'include' => [
                    'tutor',
                    'student',
                ],
            ]
        );
        // Return
        return view('admin.relationships.details.edit', compact('relationship'));
    }

    /**
     * Update the relationship.
     *
     * @param  Request $request
     * @param  integer $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // Lookup
        $relationship = $this->relationships->findById($id);
        // Attrs
        $relationship->status = $request->status;
        // Save
        $relationship->save();
        // Return
        return redirect()
            ->route('admin.relationships.details.show', [
                'id' => $id,
            ]);
    }
}

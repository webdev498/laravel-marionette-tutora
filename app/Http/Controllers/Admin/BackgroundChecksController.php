<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Presenters\BackgroundCheckPresenter;
use App\Pagination\BackgroundChecksPaginator;
use App\Repositories\Contracts\BackgroundCheckRepositoryInterface;

class BackgroundChecksController extends AdminController
{
    /**
     * @var BackgroundCheckRepositoryInterface
     */
    protected $backgroundChecks;

    /**
     * @var BackgroundChecksPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  BackgroundCheckRepositoryInterface $backgroundChecks
     * @param  BackgroundChecksPaginator          $paginator
     */
    public function __construct(
        BackgroundCheckRepositoryInterface  $backgroundChecks,
        BackgroundChecksPaginator           $paginator
    ) {
        $this->backgroundChecks = $backgroundChecks;
        $this->paginator        = $paginator;
    }

    /**
     * Show a list of background checks
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // Options
        $page    = (integer) $request->get('page', 1);
        $filter  = (string)  $request->get('filter', 'pending');
        $perPage = BackgroundChecksPaginator::PER_PAGE;

        // Lookup
        $items = $this->backgroundChecks
            ->paginate($page, $perPage)
            ->with([
                'user',
                'image',
            ])
            ->{camel_case("get_{$filter}_page")}(
                $page,
                BackgroundChecksPaginator::PER_PAGE
            );



        $count = $this->backgroundChecks
            ->{camel_case("count_{$filter}")}();

        // Paginate
        $backgroundChecks = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('admin.background_checks.index', compact('id'))
        ]);

        // Present
        $backgroundChecks = $this->presentCollection(
            $backgroundChecks,
            new BackgroundCheckPresenter(),
            [
                'include' => [
                    'user',
                    'image',
                ],
                'meta' => [
                    'count'      => $backgroundChecks->count(),
                    'total'      => $count,
                    'pagination' => $backgroundChecks->render(),
                ],
            ]
        );


        // Return
        return view('admin.background_checks.index', compact('backgroundChecks', 'filter'));
    }
}

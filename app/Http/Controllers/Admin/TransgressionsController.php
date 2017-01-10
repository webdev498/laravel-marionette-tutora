<?php

namespace App\Http\Controllers\Admin;

use App\Pagination\TransgressionsPaginator;
use App\Presenters\TransgressionPresenter;
use App\Presenters\TutorPresenter;
use App\Repositories\Contracts\TransgressionRepositoryInterface;
use Illuminate\Http\Request;

class TransgressionsController extends AdminController
{

    /**
     * @var TransgressionRepositoryInterface
     */
    protected $transgressions;
    
    /**
     * @var TransgressionsPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  TransgressionRepositoryInterface $transgressions
     * @return void
     */
    public function __construct(
        TransgressionRepositoryInterface $transgressions,
        TransgressionsPaginator $paginator
    ) {
        $this->transgressions = $transgressions;
        $this->paginator = $paginator;
    }

    /**
     * Show a list of transgressions ordered by date
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // Options
        $page   = (integer) $request->get('page', 1);
        // Lookups
        $items = $this->transgressions
            ->with([
                'message'
            ])
            ->getPage($page, 50);

        $count = $this->transgressions->count();

        // Paginate
        $transgressions = $this->paginator->paginate($items, $count, $page);
        // Present
        $transgressions = $this->presentCollection(
            $transgressions,
            new TransgressionPresenter(),
            [
                'include' => [
                    'user',
                    'user.roles',
                    'message',
                    'message.relationship',
                ],
                'meta' => [
                    'count'      => $items->count(),
                    'total'      => $count,
                    'pagination' => $transgressions
                        ->render(),
                ],
            ]
        );
        
        // Return
        return view('admin.transgressions.index', compact('transgressions'));
    }

}

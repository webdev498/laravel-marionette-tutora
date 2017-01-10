<?php

namespace App\Http\Controllers\Admin;

use App\Pagination\MessagesPaginator;
use App\Presenters\MessageLinePresenter;
use App\Presenters\MessagePresenter;
use App\Repositories\Contracts\MessageLineRepositoryInterface;
use App\Repositories\Contracts\MessageRepositoryInterface;
use Illuminate\Http\Request;

class MessagesController extends AdminController
{
    /**
     * @var MessageRepositoryInterface
     */
    protected $messages;

    /**
     * @var MessagelineRepositoryInterface
     */
    protected $lines;

    /**
     * @var MessagesPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  MessageRepositoryInterface $messages
     * @param  MessagesPaginator          $paginator
     * @return void
     */
    public function __construct(
        MessageRepositoryInterface $messages,
        MessageLineRepositoryInterface $lines,
        MessagesPaginator          $paginator
    ) {
        $this->messages  = $messages;
        $this->lines = $lines;
        $this->paginator = $paginator;
    }

    /**
     * Show a list of messages.
     *
     * @param  Request $request
     * @return void
     */
    public function index(Request $request)
    {
        // Options
        $page   = (integer) $request->get('page', 1);
        $perPage = MessagesPaginator::PER_PAGE;
        $filter = (string)  $request->get('filter');
        // Lookup
        $items = $this->lines
            ->with([
                'message.relationship.tutor',
                'message.relationship.student'
            ])
            ->paginate($page, $perPage)
            ->{$filter ? 'getBy' . $filter : 'get'}();

        $count = $this->lines
            ->{$filter ? 'countBy' . $filter : 'count'}();

        // Paginate

        $lines = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('admin.messages.index'),
        ]);

        // Present
        $lines = $this->presentCollection(
            $lines,
            new MessageLinePresenter(),
            [
                'include' => [
                    'relationship',
                    'message',
                    'tutor',
                    'student',
                ],
                'meta' => [
                    'count'      => $lines->count(),
                    'total'      => $count,
                    'pagination' => $lines->appends(compact('filter'))->render(),
                ],
            ]
        );

        $preload = [
            'lines' => $lines->data->toArrayRecursively(),
        ];

        // Return
        return view('admin.messages.index', compact('lines', 'filter', 'preload'));
    }
}

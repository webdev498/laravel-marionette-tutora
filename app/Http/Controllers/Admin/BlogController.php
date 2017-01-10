<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use Illuminate\Http\Request;
use App\Presenters\ArticlePresenter;
use App\Pagination\ArticlesPaginator;
use App\Repositories\Contracts\ArticleRepositoryInterface;

class BlogController extends AdminController
{

    /**
     * @var ArticleRepositoryInterface
     */
    protected $articles;

    /**
     * @var ArticlesPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller
     *
     * @param  ArticleRepositoryInterface $articles
     * @param  ArticlesPaginator          $paginator
     * @return void
     */
    public function __construct(
        ArticleRepositoryInterface $articles,
        ArticlesPaginator          $paginator
    ) {
        $this->articles    = $articles;
        $this->paginator = $paginator;
    }
    /**
     * Show a list of blog articles
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // Options
        $page   = (integer) $request->get('page', 1);
        $perPage = ArticlesPaginator::PER_PAGE;
        // Lookup
        $items = $this->articles
            ->paginate($page, $perPage)
            ->get();
        $count = $this->articles->count();
        $articles = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('admin.blog.index'),
        ]);
        // Present
        $articles = $this->presentCollection(
            $articles,
            new ArticlePresenter(),
            [
                'meta' => [
                    'count'      => $articles->count(),
                    'total'      => $count,
                    'pagination' => $articles->render(),
                ],
            ]
        );
        // Return
        return view('admin.blog.index', compact('articles'));
    }
}

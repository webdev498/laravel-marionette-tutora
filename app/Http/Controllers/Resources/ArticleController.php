<?php

namespace App\Http\Controllers\Resources;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Pagination\BlogPaginator;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\Filesystem;
use League\CommonMark\CommonMarkConverter;
use App\Presenters\ArticlePresenter;
use App\Pagination\ArticlesPaginator;
use App\Repositories\Contracts\ArticleRepositoryInterface;

class ArticleController extends Controller
{
    protected $css = [
    
        '/vendor/medium-editor/dist/css/medium-editor.min.css',
        '/vendor/medium-editor/dist/css/themes/default.css',
        '/vendor/medium-editor-insert-plugin/dist/css/medium-editor-insert-plugin.min.css',

    ];

    protected $js = [
        '//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-577e6c142c9f558a',
    ];

    /**
     * @var Filesystem
     */
    protected $file;


    /**
     * @var ArticleRepositoryInterface
     */
    protected $articles;

    /**
     * @var BlogPaginator
     */
    protected $paginator;

    /**
     * Create an instance of the controller.
     *
     * @param  Filesystem    $files
     * @param  ArticleRepositoryInterface $articles
     * @param  BlogPaginator $paginator
     * @return void
     */
    public function __construct(
        Filesystem    $files,
        ArticleRepositoryInterface $articles,
        BlogPaginator $paginator
    ) {
        $this->files     = $files;
        $this->articles    = $articles;
        $this->paginator = $paginator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Options
        $page    = (integer) $request->get('page', 1);
        $perPage = 8;

        $items = $this->articles
            ->paginate($page, $perPage)
            ->getPublishedByPage();
        $count = $this->articles->countPublished();
        $articles = $this->paginator->paginate($items, $count, $page, [
            'path' => relroute('articles.index'),
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
        return view('resources.articles.index', compact('articles'));

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        // Lookup
        $article = $this->articles->findBySlug($slug);
        // Abort
        if ( ! $article) {
            abort(404);
        }
        // Present
        $article = $this->presentItem(
            $article,
            new ArticlePresenter()
        );
        return view('resources.articles.show')->with([
            'css' => $this->css, 
            'js' => $this->js, 
            'article' => $article,
            'meta_title' => $article->title,
            'meta_description' => $article->preview
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Database\Exceptions\DuplicateResourceException;
use App\Geocode\Exceptions\NoResultException;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Presenters\ArticlePresenter;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Resources\Article;
use App\Validators\Exceptions\ValidationException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ArticleController extends AdminController
{

    /**
     * @var ArticleRepositoryInterface
     */
    protected $articles;

    protected $css = [
        '/vendor/medium-editor/dist/css/medium-editor.min.css',
        '/vendor/medium-editor/dist/css/themes/default.css',
        '/vendor/medium-editor-insert-plugin/dist/css/medium-editor-insert-plugin.min.css',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css',

    ];

    protected $js = [
        '/vendor/jquery/dist/jquery.min.js',
        '/vendor/medium-editor/dist/js/medium-editor.js',
        '/vendor/handlebars/handlebars.runtime.min.js',
        '/vendor/jquery-sortable/source/js/jquery-sortable-min.js',
        '/vendor/blueimp-file-upload/js/vendor/jquery.ui.widget.js',
        '/vendor/blueimp-file-upload/js/jquery.iframe-transport.js',
        '/vendor/blueimp-file-upload/js/jquery.fileupload.js',
        '/vendor/medium-editor-insert-plugin/dist/js/medium-editor-insert-plugin.js',
    ];

    /**
     * Create an instance of the controller
     *
     * @param  ArticleRepositoryInterface $articles
     * @return void
     */
    public function __construct(
        ArticleRepositoryInterface $articles
    ) {
        $this->articles = $articles;
    }

    public function index()
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @param  string $id
     * @return Response
     */

    public function show($id)
    {
        // Lookup
        $article = $this->articles->findById($id);
        // Abort
        if ( ! $article) {

            abort(404);
        }
        // Present
        $article = $this->presentItem(
            $article,
            new ArticlePresenter()
        );

        // return $article->published_at->computer;
        // Return
        //die(var_dump($article));
        return view ('admin.blog.article.show')->with(['css' => $this->css, 'js' => $this->js, 'article' => $article] );
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $article = new Article();
       
        return view ('admin.blog.article.show')->with(['css' => $this->css, 'js' => $this->js, 'article' => $article] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if(! $request->input('article_id')){
            loginfo('here');
            $article = new Article();    
        } else {
            loginfo('finding article');
            $article = $this->articles->findById($request->input('article_id'));
        }

        $article->preview = $request->input('article_preview');

        
        $article->published_at = $request->input('article_published_at');
        $article->title = $request->input('article_title');
        $article->body = $request->input('article_body');
        preg_match('/(<img)[^>]+>/', $article->body, $matches);
        $article->image = $matches[0];
        $article->published = $request->input('article_status');
        $article->user_id = Auth::user()->id;
        $deleting_images=$request->input('deleting_images');
        if($deleting_images > 0){
            foreach ($deleting_images as $image){
                $file_path = 'uploads/img/'.$image;
                if(file_exists($file_path)){
                    unlink($file_path);
                }
            }
        }
        $article->save();
        return($article->id);
    }

    public function upload(Request $request){
        $file = $request->file()['files'][0];
        $file_name = time();
        $file_name .= rand();
        $ext =  $file->getClientOriginalExtension();
        $destinationPathImg = public_path().'/uploads/img';
        //$file->move("/", $file_name.".".$ext);
        if ( $file->move($destinationPathImg, $file_name.".".$ext)) {
            return response()->json(["files" => [0 => ['url' => '/public/uploads/img/'.$file_name.".".$ext]]]);
        } else {
            return 'Error saving the file.';
        }

        //
    }

    public function delete_image(Request $request){
        $file_name = $request->input('image_name');
        $file_path = 'uploads/img/'.$file_name;
        if(file_exists($file_path)){
            return 200;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Lookup
        $article = $this->articles->findById($id);

        // Abort
        if ( ! $article) {

            abort(404);
        }
        // Delete
        $article->delete();

        // Present
        $article = $this->presentItem(
            $article,
            new ArticlePresenter()
        );
        
        // Return
        return view ('admin.blog.article.show', compact('article'));
    }
}

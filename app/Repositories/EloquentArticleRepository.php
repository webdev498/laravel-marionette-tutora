<?php

namespace App\Repositories;

use App\Resources\Article;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Contracts\ArticleRepositoryInterface;

class EloquentArticleRepository extends AbstractEloquentRepository implements ArticleRepositoryInterface
{

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Article
     */
    protected $article;

    /**
     * Create an instance of the repository
     *
     * @param  Database $database
     * @param  Article    $article
     * @return void
     */
    public function __construct(
        Database $database,
        Article  $article
    ) {
        $this->database = $database;
        $this->article  = $article;
    }

    /**
     * Get articles
     *
     * @return Collection
     */
    public function get()
    {
        return $this->article
            ->newQuery()
            ->orderBy('updated_at', 'desc')
            ->with($this->with)
            ->get();
    }

    /**
     * Get articles that have been published
     *
     * @return Collection
     */
    public function getPublished()
    {
        return $this->article
            ->newQuery()
            ->published()
            ->orderBy('published_at', 'desc')
            ->with($this->with)
            ->get();
    }


    /**
     * Get articles that have been published by page
     *
     * @return Collection
     */
    public function getPublishedByPage()
    {
        return $this->article
            ->published()
            ->takePage($this->page, $this->perPage)
            ->orderBy('published_at', 'desc')
            ->with($this->with)
            ->get();
    }

    /**
     * Count the articles
     *
     * @return integer
     */
    public function count()
    {
        return $this->article
            ->newQuery()
            ->count();
    }


    /**
     * Count the published sarticles
     *
     * @return integer
     */
    public function countPublished()
    {
        return $this->article
            ->newQuery()
            ->published()
            ->count();
    }
    /**
     * Find an article by a given id
     *
     * @param  string $id
     * @return Article|null
     */
    public function findById($id)
    {
        return $this->article
            ->withTrashed()
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Find an article by a given id
     *
     * @param  string $id
     * @return Article|null
     */
    public function findBySlug($slug)
    {
        return $this->article
            ->published()
            ->where('slug', '=', $slug)
            ->first();
    }
}

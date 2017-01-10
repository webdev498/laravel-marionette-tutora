<?php

namespace App\Presenters;

use App\Resources\Article;

class ArticlePresenter extends AbstractPresenter
{

    /**
     * List of resources that are included by default.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'user',
    ];

    public function transform(Article $article)
    {
        return [
            'id'       => (string) $article->id,
            'user_id'       => (string) $article->user_id,
            'title' => (string) $article->title,
            'slug' => (string) $article->slug,
            'body' => (string) $article->body,
            'image' => (string) $article->image,
            'preview' => (string) $article->preview,
            'published' => (boolean) $article->published,
            'published_at' => $this->formatDate($article->published_at),
            'created_at' => $this->formatDate($article->created_at),
            'updated_at' => $this->formatDate($article->updated_at),
            'deleted_at' => $article->deleted_at,
        ];
    }

    /**
     * Include the user
     *
     * @return array
     */
    protected function includeUser(Article $article)
    {
        $user = $article->user;

        return $this->item($user, new UserPresenter());
    }

}

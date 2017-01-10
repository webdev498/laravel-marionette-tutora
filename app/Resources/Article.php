<?php

namespace App\Resources;

use App\Database\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\Sluggable;

class Article extends Model 
{
    use SoftDeletes;
    use Sluggable;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'resources_articles';

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    protected $fillable = [
        'title', 'preview', 'slug', 'body', 'published', 'published_at',
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['published_at', 'deleted_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * An article has one user
     *
     * @return HasOne
     */

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * An article has many 
     *
     * @return HasOne
     */

    /**
     * The tags that belong to the article.
     * @return MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany('App\Resources\Tag', 'taggable');
    }

    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }
}

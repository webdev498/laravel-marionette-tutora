<?php

namespace App\Resources;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'resources_tags';

    /**
     * The tags belongs to many articles
     * @return BelongsToMany
     */

    public function articles()
    {
        return $this->morphedByMany('App\Resources\Post', 'resources_taggable');
    }
}

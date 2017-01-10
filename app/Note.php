<?php

namespace App;

use App\Database\Model;

class Note extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
    ];

    /**
     * Many things can be taskable
     *
     * @return MorphTo
     */
    public function noteable()
    {
        return $this->morphTo();
    }
}

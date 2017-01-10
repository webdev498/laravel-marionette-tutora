<?php

namespace App;

use Cache;
use App\Database\NestedSetModel;
use Watson\Rememberable\Rememberable;
use App\Observers\SubjectObserver;

class Subject extends NestedSetModel
{

    use Rememberable;

    //Remember cache for around a couple of seconds. Stops same queries being reproduced on same request.
    // protected $rememberFor = 0.1;

    /**
     * Boot the model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        parent::observe(app(SubjectObserver::class));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes to append to the model's array and JSON form.
     *
     * @var array
     */
    protected $appends = [
        'path',
        'title',
        'children',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'left',
        'right',
        'created_at',
        'updated_at',
        'pivot',
    ];

    /**
     * A subject belongs to many users
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User')
            ->withTimestamps();
    }

    /**
     * A subject has many searches
     *
     * @return HasMany
     */
    public function searches()
    {
        return $this->hasMany('App\Search');
    }

    /**
     * A subject has many jobs
     *
     * @return HasMany
     */
    public function jobs()
    {
        return $this->hasMany('App\Job');
    }


    /**
     * Mutate the title attribute.
     *
     * @return String
     */
    public function getTitleAttribute()
    {
        $parts = explode(' / ', $this->path);
        $parts = array_slice($parts, 1);
        $main  = array_shift($parts);

        if ( ! $parts) {
            return $main;
        }

        return sprintf('%s (%s)', $main, implode($parts, ', '));
    }

    /**
     * Mutate the path attribute
     *
     * @return String
     */
    public function getPathAttribute()
    {
        $path = Cache::rememberForever('subject.path.'.$this->id, function() {
            if($this->parent_id && $parent = $this->parent) {
                $path = $parent->path.' / '.$this->name;
            } else {
                $path = $this->name;
            }
            return $path;
        });

        return $path;

    }
}

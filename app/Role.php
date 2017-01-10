<?php namespace App;

use App\Database\Model;
use Watson\Rememberable\Rememberable;

class Role extends Model
{
    use Rememberable;

    protected $rememberFor = 60;

    const TUTOR   = 'tutor';
    const STUDENT = 'student';
    const ADMIN   = 'admin';

    /**
     * A role belongs to many users.
     *
     * @return BelongsTo
     */
    public function users()
    {
        return $this->belongsToMany('App\User')
            ->withTimestamps();
    }

}

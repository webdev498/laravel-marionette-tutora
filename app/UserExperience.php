<?php namespace App;

use App\Database\Model;

class UserExperience extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'years_tutoring',
        'years_teaching',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * A experience belongs to many users
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsTo('App\User');
    }

}

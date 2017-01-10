<?php namespace App;

use App\Database\Model;

class UserQualificationUniversity extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'university',
        'level',
        'subject',
        'still_studying',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Create a new instance of a university
     *
     * @param  String  $subject
     * @param  String  $location
     * @param  String  $level
     * @param  Boolean $stillStudying
     * @return UserQualificationUniversity
     */
    public static function attend(
        $subject,
        $location,
        $level,
        $stillStudying
    ) {
        $university = new static();

        $university->subject        = $subject;
        $university->university     = $location;
        $university->level          = $level;
        $university->still_studying = $stillStudying;

        return $university;
    }

    /**
     * University qualification belong to a user
     *
     * @return BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Mutate the still studying attribute
     *
     * @param  String $value
     * @return Boolean
     */
    public function getStillStudyingAttribute($value)
    {
        return (bool) $value;
    }

}

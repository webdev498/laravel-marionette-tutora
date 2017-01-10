<?php namespace App;

use App\Database\Model;

class UserQualificationOther extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location',
        'grade',
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
     * Create a new instance of a other
     *
     * @param  String  $subject
     * @param  String  $location
     * @param  String  $grade
     * @param  Boolean $stillStudying
     * @return UserQualificationOther
     */
    public static function attend(
        $subject,
        $location,
        $grade,
        $stillStudying
    ) {
        $other = new static();

        $other->subject        = $subject;
        $other->location       = $location;
        $other->grade          = $grade;
        $other->still_studying = $stillStudying;

        return $other;
    }

    /**
     * Other qualification belongs to a user.
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

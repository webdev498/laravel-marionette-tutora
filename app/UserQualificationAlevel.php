<?php namespace App;

use App\Database\Model;

class UserQualificationAlevel extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'college',
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
     * Create a new instance of a college
     *
     * @param  String  $subject
     * @param  String  $location
     * @param  String  $grade
     * @param  Boolean $stillStudying
     * @return UserQualificationAlevel
     */
    public static function attend(
        $subject,
        $location,
        $grade,
        $stillStudying
    ) {
        $alevel = new static();

        $alevel->subject        = $subject;
        $alevel->college        = $location;
        $alevel->grade          = $grade;
        $alevel->still_studying = $stillStudying;

        return $alevel;
    }

    /**
     * A-Level qualifications belong to a user.
     *
     * @return BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Mutate the still studying attribute.
     *
     * @param  String $value
     * @return Boolean
     */
    public function getStillStudyingAttribute($value)
    {
        return (bool) $value;
    }

}

<?php namespace App;

use App\Database\Model;

class UserQualificationTeacherStatus extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'level',
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
     * Create a new instance of a qts
     *
     * @param  String  $level
     * @return UserQualificationTeacherStatus
     */
    public static function attend(
        $level
    ) {
        $qts = new static();

        $qts->level = $level;

        return $qts;
    }

    /**
     * A QTS belongs to a user.
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

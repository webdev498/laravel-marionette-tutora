<?php

namespace App;

use App\Database\Model;
use App\Events\UserRequirementIsPending;
use App\Events\UserRequirementWasCompleted;
use App\Events\UserRequirementIsIncomplete;

class UserRequirementCollection extends \Illuminate\Database\Eloquent\Collection
{

    public function areCompleted()
    {
        // Filtered
        $required     = $this->where('is_optional', false)
                             ->where('for', UserRequirement::PROFILE_INFORMATION);
        $completed    = $this->where('is_completed', true)
                             ->where('for', UserRequirement::PROFILE_INFORMATION);

        // Count filtered
        $requiredCount  = $required->count();
        $completedCount = $completed->count();

        // Completed
        return $requiredCount === $completedCount;
    }

    public function areCompletedFor($section) 
    {
        // Filtered
        $required     = $this->where('is_optional', false)
                             ->where('for', $section);
        $completed    = $this->where('is_completed', true)
                             ->where('for', $section);

        // Count filtered
        $requiredCount  = $required->count();
        $completedCount = $completed->count();

        // Completed
        return $requiredCount === $completedCount;
    }

    public function onlyPending($requirement)
    {
        
        $unfinished = $this->where('is_completed', false)
                        ->where('is_optional', false);                       

        $unfinishedCount = $unfinished->count();

                        
        if ($unfinishedCount === 1) {
            return $unfinished->contains('name', $requirement);
        }   
        
        return false;
    }

    public function forProfileInformation()
    {
        return $this->where('for', UserRequirement::PROFILE_INFORMATION);
    }

    public function forProfileSubmit()
    {
        return $this->where('for', UserRequirement::PROFILE_SUBMIT);
    }

    public function forPayouts() 
    {
        return $this->where('for', UserRequirement::PAYOUTS);
    }
}

class UserRequirement extends Model
{
    // Names
    const TAGLINE                  = 'tagline';
    const RATE                     = 'rate';
    const TRAVEL_POLICY            = 'travel_policy';
    const BIO                      = 'bio';
    const PROFILE_PICTURE          = 'profile_picture';
    const SUBJECTS                 = 'subjects';
    const QUALIFICATIONS           = 'qualifications';
    const QUALIFIED_TEACHER_STATUS = 'qualified_teacher_status';
    const PAYMENT_DETAILS          = 'payment_details';
    const IDENTIFICATION           = 'identification';
    const BACKGROUND_CHECK         = 'background_check';
    const QUIZ_QUESTIONS           = 'quiz_questions';
    const PERSONAL_VIDEO           = 'personal_video';

    // Sections
    const PROFILE = 'profile';
    const QUIZ    = 'quiz';
    const ACCOUNT = 'account';
    const OTHER   = 'other';

    // For
    const PROFILE_INFORMATION = 'profile_information';
    const PROFILE_SUBMIT      = 'profile_submit';
    const PAYOUTS             = 'payouts';
    const ANY                 = 'any';

    public $meta = null;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_optional'  => 'boolean',
        'is_pending'   => 'boolean',
        'is_completed' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'section',
        'for',
        'is_optional'
    ];

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return UserRequirementCollection
     */
    public function newCollection(Array $models = [])
    {
        return new UserRequirementCollection($models);
    }

    /**
     * Make a user requirement
     *
     * @return self
     */
    public static function make(Array $attributes = [])
    {
        return new self($attributes);
    }

    /**
     * Complete a given user requirement
     *
     * @param  UserRequirement $requirement
     * @return UserRequirement
     */
    public static function pend(UserRequirement $requirement)
    {
        $requirement->is_pending = true;
        $requirement->raise(new UserRequirementIsPending($requirement));
        return $requirement;
    }

    /**
     * Complete a given user requirement
     *
     * @param  UserRequirement $requirement
     * @return UserRequirement
     */
    public static function complete(UserRequirement $requirement)
    {
        $requirement->is_completed = true;
        $requirement->is_pending   = false;

        $requirement->raise(new UserRequirementWasCompleted($requirement));

        return $requirement;
    }

    /**
     * Incomplete a given user requirement
     *
     * @param  UserRequirement $requirement
     * @return UserRequirement
     */
    public static function incomplete(UserRequirement $requirement)
    {
        $requirement->is_completed = false;
        $requirement->is_pending   = false;

        $requirement->raise(new UserRequirementIsIncomplete($requirement));

        return $requirement;
    }

    /**
     * A requirement belongs to a tutor.
     *
     * @return BelongsTo
     */
    public function tutor()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}

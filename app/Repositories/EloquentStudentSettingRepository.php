<?php namespace App\Repositories;

use App\Repositories\Contracts\StudentSettingRepositoryInterface;
use App\Student;
use App\StudentSetting;

class EloquentStudentSettingRepository implements StudentSettingRepositoryInterface
{
	protected $studentSettings;

	public function __construct(StudentSetting $settings)
	{
		$this->settings = $settings;
	}

	/**
     * Save a settings for a user
     *
     * @param  User        $user
     * @param  UserProfile $profile
     * @return UserProfile
     */
    public function saveForStudent(Student $student, StudentSetting $settings)
    {
        if ( ! $student->settings()->save($settings)) {
            throw new ResourceNotPersistedException();
        }

        return $settings;
    }

}
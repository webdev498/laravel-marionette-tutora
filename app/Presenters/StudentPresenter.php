<?php

namespace App\Presenters;

use App\Student;

class StudentPresenter extends UserPresenter
{
    /**
     * Include the searches
     *
     * @param  User $user
     * @return Collection
     */
    protected function includeSettings(Student $student)
    {
		return $this->item($student->settings, new StudentSettingsPresenter());
    }  
}

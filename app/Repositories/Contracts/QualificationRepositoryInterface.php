<?php namespace App\Repositories\Contracts;

use App\Tutor;

interface QualificationRepositoryInterface
{

    /**
     * Delete all qualifications belonging to a user
     *
     * @param  Tutor $tutor
     * @return void
     */
    public function deleteByTutor(Tutor $tutor);

}

<?php namespace App\Repositories\Contracts;

use App\Tutor;

interface StudentRepositoryInterface
{

    /**
     * Get the students belonging to a tutor
     *
     * @param  Tutor   $tutor
     * @param  Integer $page
     * @param  Integer $perPage
     * @return Collection
     */
    public function getByTutor(Tutor $tutor, $page, $perPage);

    /**
     * Count the number of students that belong to a tutor
     *
     * @param  Tutor $tutor
     * @return integer
     */
    public function countByTutor(Tutor $tutor);

}

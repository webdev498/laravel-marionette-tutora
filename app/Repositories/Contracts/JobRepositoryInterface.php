<?php namespace App\Repositories\Contracts;

use App\Job;
use App\Student;
use App\Tutor;
use Illuminate\Database\Eloquent\Collection;

interface JobRepositoryInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $uuid
     *
     * @return mixed
     */
    public function findByUuid($uuid);

    /**
     * @param Job $job
     *
     * @return bool
     */
    public function save(Job $job);

    /**
     * @param Job $job
     *
     * @return bool
     */
    public function delete(Job $job);

    /**
     * @param \DateTime $date
     *
     * @return Collection
     */
    public function getOpenedBeforeDate(\DateTime $date);

    /**
     * @param  Student $student
     * @param  Tutor   $tutor
     *
     * @return Collection
     */
    public function getByTutorAndStudent(Student $student, Tutor $tutor);
}

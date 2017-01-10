<?php namespace App\Repositories\Contracts;

use App\Subject;

interface SubjectRepositoryInterface
{

   
    /**
     * Find the subject with a given id
     *
     * @param Integer $id
     * @return Subject
     */
    public function findById($id);

    /**
     * Get the subjects by given ids
     *
     * @param  Array $ids
     * @return Collection
     */
    public function getByIds(Array $ids);

    /**
     * Get all subjects that are at a given depth
     *
     * @param  Integer $depth
     * @return Collection
     */
    public function getByDepth($depth);

    /**
     * Get all subjects that're decendents of a given depth, in their
     * default order, eageriy linked.
     *
     * @param  Integer $depth
     * @return Collection
     */
    public function getDescendentsByDepth($depth);

}

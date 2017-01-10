<?php

namespace App\Repositories\Contracts;

use App\Tutor;

interface RelationshipRepositoryInterface
{

    /**
     * Get the relationships the given tutor belongs to.
     * Results are paginated.
     *
     * @param  Tutor   $tutor
     * @param  Integer $page
     * @param  Integer $perPage
     * @return Collection
     */
    public function getByTutor(Tutor $tutor, $page, $perPage);

    /**
     * Count the number of relationships that a given tutor belongs to.
     *
     * @param  Tutor $tutor
     * @return integer
     */
    public function countByTutor(Tutor $tutor);

}

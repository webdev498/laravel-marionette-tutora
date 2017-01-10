<?php

namespace App\Repositories;

use App\User;
use App\IdentityDocument;
use App\Repositories\Contracts\IdentityDocumentRepositoryInterface;

class EloquentIdentityDocumentRepository implements IdentityDocumentRepositoryInterface
{

    /**
     * @var IdentityDocument
     */
    protected $identityDocument;

    /**
     * Create an instance of the repository
     *
     * @param  IdentityDocuemtn $identityDocument
     * @return void
     */
    public function __construct(IdentityDocument $identityDocument)
    {
        $this->identityDocument = $identityDocument;
    }

    public function saveForUser(User $user, IdentityDocument $identityDocument)
    {
        $user->identityDocument()->save($identityDocument);

        return $identityDocument;
    }

    /**
     * Count the number of ids by a given uuid
     *
     * @param  string $uuid
     * @return integer
     */
    public function countByUuid($uuid)
    {
        return $this->identityDocument
            ->newQuery()
            ->where('uuid', '=', $uuid)
            ->count();
    }
}

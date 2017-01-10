<?php namespace App\Repositories\Contracts;

use App\Role;
use App\User;

interface RoleRepositoryInterface
{

    /**
     * Attach (save) a role to a given user.
     *
     * @param  User $user
     * @param  Role $role
     * @return Role
     */
    public function saveForUser(User $user, Role $role);

    /**
     * Find a role by a given name
     *
     * @param  string $name
     * @return Role|null
     */
    public function findByName($name);

}

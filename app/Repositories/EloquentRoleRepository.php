<?php namespace App\Repositories;

use App\Role;
use App\User;
use App\Repositories\Contracts\RoleRepositoryInterface;

class EloquentRoleRepository implements RoleRepositoryInterface
{

    /**
     * @var Role
     */
    protected $role;

    /**
     * Create an instance of this repository.
     *
     * @param  Role $role
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Attach (save) a role to a given user.
     *
     * @param  User $user
     * @param  Role $role
     * @return Role
     */
    public function saveForUser(User $user, Role $role)
    {
        if ($user->roles()->attach($role) === false) {
            throw new \Exception('Resource not saved!');
        }

        return $role;
    }

    /**
     * Find a role by a given name
     *
     * @param  string $name
     * @return Role|null
     */
    public function findByName($name)
    {
        return $this->role->whereName($name)->first();
    }
}

<?php namespace App\Repositories;

use App\Role;
use App\User;
use Illuminate\Contracts\Cache\Repository as Cache;
use App\Repositories\Contracts\RoleRepositoryInterface;

class CachingRoleRepository implements RoleRepositoryInterface
{

    protected $cache;

    protected $roles;

    public function __construct(
        Cache                    $cache,
        RoleRepositoryInterface $roles
    ) {
        $this->cache    = $cache;
        $this->roles = $roles;
    }

    public function saveForUser(User $user, Role $role)
    {
        return $this->roles->saveForUser($user, $role);
    }

    public function findByName($name)
    {
        return $this->cache->remember("repository.role.{$name}", 30, function () use ($name) {
            return $this->roles->findByName($name);
        });
    }
}
